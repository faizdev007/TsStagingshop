<div class="mt-5 details-duty rhs-contact-form c-bg-text-color4">

    <div class="-title-v2 f-25 f-two text-center">Stamp Duty Calculator</div>
    <div class="details-duty-tab">

        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active stmp-calculator--tab" data-type="single" data-toggle="tab" href="#duty-1" role="tab">
                    <p>SINGLE PROPERTY</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link stmp-calculator--tab" data-type="additional" data-toggle="tab" href="#duty-2" role="tab">
                    <p>ADDITIONAL PROPERTY</p>
                </a>
            </li>
        </ul>
        <div class="tab-content">
           
            <div class="details-duty-content stmp-calculator--trigger-container">
                 <i class="fas fa-spinner fa-spin input-loader"></i>
                <input type="text" class="stmp-calculator--trigger price--format single" value="{{!empty($property->price)?$property->price:''}}" placeholder="£1,000,000">
            </div>
        </div>
        <div class="details-duty-table">
            <table>
                <thead>
                    <tr>
                        <th>TAX BAND</th>
                        <th>%</th>
                        <th>TAXABLE SUM</th>
                        <th>TAX</th>
                    </tr>
                </thead>
                <tbody class="stmp--tax-table-results">
                    <tr>
                        <td>Less than £300k</td>
                        <td>0</td>
                        <td>£0</td>
                        <td>£0</td>
                    </tr>

                    <tr>
                        <td>£300k - £500k</td>
                        <td>5</td>
                        <td>£0</td>
                        <td>£0</td>
                    </tr>
                    <tr>
                        <td>£500k - £925k</td>
                        <td>5</td>
                        <td>£0</td>
                        <td>£0</td>
                    </tr>
                    <tr>
                        <td>£925 - £1.5m</td>
                        <td>10</td>
                        <td>£0</td>
                        <td>£0</td>
                    </tr>
                    <tr>
                        <td>Over £1.5m</td>
                        <td>12</td>
                        <td>£0</td>
                        <td>£0</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="stmp-effective-rate">
            <div class="row">
                <div class="col-md-6 stmp-effective-rate-padding-10 stmp-effective-rate-bg-header">
                    <strong class="stmp-effective-rate-margin-7">Effective rate</strong>
                </div>
                <div class="col-md-6 stmp-effective-rate-padding-10 stmp-effective-rate-background">
                    <span class="text-white-stmp value-smpt-effective">0.0%</span>
                </div>
            </div>
        </div>
        <div class="button-wrapper">
        <button>CALCULATE</button>
        </div>
        <div class="details-bottom stmp--tax-table-results-container d-none text-center">

            <h4 class="f-14 text-uppercase">Your stamp duty</h4>
            <span class="stmp--estimated-result">£28,750</span>
        </div>
    </div>
</div>
