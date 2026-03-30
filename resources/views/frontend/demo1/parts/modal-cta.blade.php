
<!-- Modal -->
<div class="modal fade modal-style-1 modal-cat-1" tabindex="-1" role="dialog" aria-labelledby="modal-style-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @php $ctaID = 1; $ctaTitle = 'Please find me a home'; @endphp
                @include('frontend.demo1.forms.home-cta-1', ['type' => 'dream-home'])
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade modal-style-1 modal-cat-2" tabindex="-1" role="dialog" aria-labelledby="modal-style-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @php $ctaID = 2; $ctaTitle = 'What’s my home worth?'; @endphp
                @include('frontend.demo1.forms.home-cta-1', ['type' => 'home-worth'])
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade modal-style-1 modal-cat-3" tabindex="-1" role="dialog" aria-labelledby="modal-style-3" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @php $ctaID = 3; $ctaTitle = 'Sell my home'; @endphp
                @include('frontend.demo1.forms.home-cta-1',['type' => 'sell-home'])
            </div>
        </div>
    </div>
</div>
