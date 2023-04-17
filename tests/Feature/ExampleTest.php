<?php

// phpcs:disable
it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
// phpcs:enable
