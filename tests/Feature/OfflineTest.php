<?php

namespace Tests\Feature;

test('index is shown without authentication', function () {
    $response = $this->get(route('offline.index'));

    $response->assertSuccessful();
    $response->assertViewIs('offline');
});
