<?php

namespace App\Policies;

use App\Models\DeliveryNote;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeliveryNotePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('delivery-notes.view');
    }

    public function view(User $user, DeliveryNote $deliveryNote): bool
    {
        return $user->can('delivery-notes.view');
    }

    public function create(User $user): bool
    {
        return $user->can('delivery-notes.create');
    }

    public function update(User $user, DeliveryNote $deliveryNote): bool
    {
        if($deliveryNote->status === 'finished') {
            return false;
        }

        return $user->can('delivery-notes.update');
    }

    public function delete(User $user, DeliveryNote $deliveryNote): bool
    {
        if($deliveryNote->status === 'finished') {
            return false;
        }

        if($deliveryNote->status === 'signed') {
            return $user->can('inspection-reports.approve');
        }

        return $user->can('delivery-notes.delete');
    }

    public function email(User $user, DeliveryNote $deliveryNote): bool
    {
        return $user->can('delivery-notes.email');
    }

    public function createPdf(User $user, DeliveryNote $deliveryNote): bool
    {
        return $user->can('delivery-notes.createpdf');
    }

    public function emailSignatureRequest(User $user, DeliveryNote $deliveryNote): bool
    {
        if($deliveryNote->status !== 'new') {
            return false;
        }

        return $user->can('delivery-notes.send-signature-request');
    }

    public function emailDownloadRequest(User $user, DeliveryNote $deliveryNote): bool
    {
        if($deliveryNote->status !== 'new') {
            return false;
        }

        return $user->can('delivery-notes.send-download-request');
    }

    public function sign(User $user, DeliveryNote $deliveryNote): bool
    {
        if($deliveryNote->status !== 'new') {
            return false;
        }

        return $user->can('delivery-notes.get-signature');
    }

    public function approve(User $user, DeliveryNote $deliveryNote): bool
    {
        return $user->can('delivery-notes.approve');
    }
}
