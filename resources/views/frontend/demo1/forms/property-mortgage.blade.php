<?php $form_id = 'mortgage'; ?>
<div class="rhs-contact-form c-bg-text-color4">
    <div class="-title-v2 f-25 f-two text-center">Mortgage Calculator</div>
    <div id = "response-<?=$form_id?>"></div>
    <form id = "ajax-form-<?=$form_id?>" action="{{url('calculate-mortgage')}}" method="post">@csrf
    <div class="-fields">
        <input name="contribution" type="hidden" placeholder="eg. 10000" value="0">
        <div class="form-group form-group-1 -labeled -transparent-bg u-mb1">
            <label class="f-label f-14">MORTGAGE AMOUNT (£)</label>
            <input name="loan" type="text" value="<?=$property->price?>" placeholder="eg. 495000">
        </div>
        <div class="form-group form-group-1 -labeled -transparent-bg u-mb1">
            <label class="f-label f-14">INTEREST RATE (%)</label>
            <input name="rate" type="text" placeholder="eg. 3.5%">
        </div>
        <div class="form-group form-group-1 -labeled -transparent-bg u-mb1">
            <label class="f-label f-14">MORTGAGE PERIOD (years)</label>
            <input name="year" type="text" placeholder="eg. 20">
        </div>
        <div class="text-center">
            <button id="btn-<?=$form_id?>" class="button button-o -primary f-16 u-rounded-0 u-mt1 u-mb105" type="submit" name="button">CALCULATE</button>
        </div>
    </div>
    </form>
</div>
<div class="rhs-mortgage-result text-center mortgage-result-wrap">
    <div class="rhs-mr-style-1 f-14">MONTHLY PAYMENTS</div>
    <div id="mortgage_total" class="rhs-mr-style-2 f-36 f-600 c-secondary1">£0</div>
</div>
