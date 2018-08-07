<?php

namespace Coedition\EDI;

interface CreateEdi {
    const SENDERID = 'COEDITION';
    const RECEIVERID = 'PARTNER';

    public function __invoke(
        $order_id,
        array $order_body,
        int $interchange_control_num,
        int $transaction_control_num
    ) : string;
}
