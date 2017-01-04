<?php

namespace app\models\lookup;

/**
 * Description of PaymentMethod
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class PaymentMethod extends BaseLookup
{
    public $code;
    public $value;
    public $payment_method;
    public $description;
    public $expire;
    public $available;
    public $description_url;
    public $icon_url;

}
