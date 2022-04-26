<template>
<div>
        <div class="custom-file">
            <input class="custom-file-input" type="file" :accept="accept" multiple="multiple" id="new_attachments" name="new_attachments[]" @change="addNewAttachments" />
            <label class="custom-file-label" for="new_attachments">Anhänge auswählen</label>
        </div>

        <div v-if="remove_attachments.length" v-for="attachment in remove_attachments">
            <input v-if="remove_attachments.length" type="hidden" :id="attachment.id" name="remove_attachments[]" :value="attachment.id" />
        </div>

        <div v-if="existing_attachments.length || new_attachments.length" class="container-fluid mt-2">

            <div v-if="existing_attachments.length" class="row py-2 align-items-center hover-highlight" v-for="attachment in existing_attachments">
                <div class="col d-inline-flex align-items-center">
                    <img v-if="attachment.url !== null" class="attachment-img-preview mr-2" :src="attachment.url" :alt="attachment.file_name" />
                    <svg v-else class="feather attachment-img-preview mr-2">
                        <use xlink:href="/svg/feather-sprite.svg#file-text"></use>
                    </svg>
                    <div>
                        <div>{{attachment.file_name}}</div>
                        <div class="text-muted">{{humanFileSize(attachment.size)}}</div>
                    </div>
                </div>
                <div class="col-auto ml-auto">
                    <button type="button" class="btn btn-sm btn-outline-danger" @click="removeExistingAttachment(attachment)">Entfernen</button>
                </div>
            </div>

            <div v-if="new_attachments.length" class="row py-2 align-items-center hover-highlight" v-for="attachment in new_attachments">
                <div class="col d-inline-flex align-items-center">
                    <img v-if="attachment.preview !== null" class="attachment-img-preview mr-2" :src="attachment.preview" :alt="attachment.file_name" />
                    <svg v-else class="feather attachment-img-preview mr-2">
                        <use xlink:href="/svg/feather-sprite.svg#file-text"></use>
                    </svg>
                    <div>
                        <div contenteditable="true" @keydown.enter.prevent @blur="changeNewAttachmentName($event, attachment)" v-html="attachment.file_name"></div>
                        <div class="text-muted">{{humanFileSize(attachment.file.size)}}</div>
                    </div>

                </div>
                <div class="col-auto ml-auto">
                    <button type="button" class="btn btn-sm btn-outline-danger" @click="removeNewAttachment(attachment)">Entfernen</button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        name: "AttachmentsSelector",

        data() {
            return {
                new_attachments: [],
                existing_attachments: this.current_attachments ? this.current_attachments : [],
                remove_attachments: [],
            }
        },

        methods: {
            addNewAttachments(event) {
                if(event.target.files) {
                    Array.from(event.target.files).forEach((file) => {
                        if(file.type.match(this.accept.replace(/\s*,\s*|\s+,/g, '|'))) {
                            if(file.type.match('image/*')) {
                                const reader = new FileReader();
                                reader.onload = () => {
                                    let attachment = {
                                        file: file,
                                        preview: String(reader.result),
                                        file_name: file.name,
                                        extension: this.fileNameExtension(file.name)
                                    };
                                    this.new_attachments.push(attachment);
                                    this.refreshNewAttachments();
                                }
                                reader.readAsDataURL(file);
                            }
                            else {
                                let attachment = {
                                    file: file,
                                    preview: null,
                                    file_name: file.name,
                                    extension: this.fileNameExtension(file.name)
                                };
                                this.new_attachments.push(attachment);
                                this.refreshNewAttachments();
                            }

                        }
                    });
                }
            },

            changeNewAttachmentName(event, value) {
                var file_name = event.target.innerText.replace(/(\r\n|\n|\r)/gm, "");

                if(file_name === '' || file_name === value.extension) {
                    event.target.innerHTML = value.file_name;
                    return;
                }

                if(!file_name.endsWith(value.extension)) {
                    value.file_name = file_name.endsWith('.') ? file_name.concat(value.extension) : file_name.concat('.', value.extension);
                }

                event.target.innerText = value.file_name;

                this.refreshNewAttachments();
            },

            removeNewAttachment(value) {
                this.new_attachments = this.removeFromArray(this.new_attachments, value);
                this.refreshNewAttachments();
            },

            removeExistingAttachment(value) {
                this.existing_attachments = this.removeFromArray(this.existing_attachments, value);
                this.remove_attachments.push(value);
            },

            removeFromArray(attachments, value) {
                return attachments.filter(attachment => {
                    return attachment !== value;
                });
            },

            refreshNewAttachments() {
                var attachments = new DataTransfer();

                this.new_attachments.forEach(function (attachment) {
                    attachments.items.add(new File([attachment.file], attachment.file_name));
                });

                document.getElementById('new_attachments').files = attachments.files;
                console.log(document.getElementById('new_attachments').files);
            },

            humanFileSize(size) {
                var i = size == 0 ? 0 : Math.floor( Math.log(size) / Math.log(1024) );
                return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'KB', 'MB', 'GB', 'TB'][i];
            },

            fileNameExtension(value) {
                let re = /(?:\.([^.]+))?$/;
                return re.exec(value)[1];
            }
        },

        props: {
            accept: {
                type: String,
                default() {
                    return 'image/*, application/pdf';
                }
            },
            current_attachments: {
                type: Array,
                default() {
                    return [];
                }
            }
        }

    }
</script>
