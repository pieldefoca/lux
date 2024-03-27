<?php

test('example', function () {
    $response = $this->get('/admin');

    $response->assertStatus(200);
});
