<?php

use Coedition\EDI\EDI;

describe('EDI', function() {
    it('can stub!', function() {
        expect((new EDI)->stub('foo'))->equal('foo');
    });
});
