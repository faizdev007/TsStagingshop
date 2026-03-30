<!-- begin footer block -->
<div class="footer-block">
    <div class="centering">

        <div class="first bx">

            <h3>Information</h3>
            <div class="cntnts">
                <ul>
                    <li><a href="{{url('aboutus')}}">About us</a></li>
                    <li><a href="{{url('contact-us')}}">Contact us</a></li>
                    <li><a href="{{url('buyers-guide')}}">Guides</a></li>
                    <li><a href="{{url('blog-news')}}">Blog & News Updates</a></li>
                    <li><a href="{{url('koh-samui-property-for-rent')}}">Koh Samui Property for Rent</a></li>
                    <li><a href="{{url('koh-samui-property-for-sale')}}">Koh Samui Property for Sale</a></li>
                    <li><a href="{{url('koh-samui-luxury-villa-for-sale')}}">Luxury Villas for Sale</a></li>
                    <li><a href="{{url('koh-samui-luxury-villa-for-rent')}}">Luxury Villas for Rent</a></li>
                    <li><a href="{{url('thailand-villas-for-sale/beachfront-villas')}}">Beachfront Villas</a></li>
                    <li><a href="{{url('koh-samui-land-for-sale/beachfront-land')}}">Beachfront Land</a></li>
                </ul>
            </div>




        </div>

        <div class="second bx">
			<h3>Popular Searches</h3>
            <div class="cntnts">
				<ul>
					<li><a href='/property-for-sale-in-thailand'>Property for Sale in Thailand</a></li>
					<li><a href='/koh-samui-property-for-sale'>Property for Sale in Koh Samui</a></li>
					<li><a href='/property-for-sale-phuket'>Property for Sale in Phuket</a></li>
					<li><a href='/property-for-sale-bangkok'>Property for Sale in Bangkok</a></li>
					<li><a href='/property-for-sale-koh-phangan'>Property for Sale in Koh Phangan</a></li>
					<li><a href='/covid-property-discounts-thailand'>Covid Special Offers</a></li>
				</ul>
            </div>
			<!--
            <h3>Search Areas in Samui</h3>
            <div class="cntnts">
                <?php $area = \App\Area::where('status','=','1')->with('city.province.country')->whereHas('city',function ($q){
                    $q->where('province_id',1);
                })->get();

                $i = 1;
                echo "<p>";
                foreach($area as $a):
                    $url = url('search/'.$a->city->province->country->slug.'/'.$a->city->province->slug.'/'.$a->city->slug.'/'.$a->slug);
                    if($i==1)
                        echo  "<a href='".$url."'>".$a->name."</a>";
                    else
                        echo ', '."<a href='".$url."'>".$a->name."</a>";
                    $i++;
                endforeach;
                echo "</p>";
                ?>
            </div>-->
            

        </div>

        <div class="third bx">

            <h3>Property Guides</h3>
            <div class="cntnts">
                <ul><?php $guide = \App\Guide::getBuyerGuide();
                    foreach($guide as $res):?>
                        <li><a href="{{$res->getUrl()}}"><?php echo $res->name;?></a></li>
                    <?php  endforeach;
                    ?>
                </ul>
            </div>


        </div>
        <style>
            /*::placeholder{*/
            /*    color: #ffffff;*/
            /*}*/
            .controls ::placeholder {
                color: #fff;
            // opacity: 1; /* Firefox */
            }

            .controls :-ms-input-placeholder { /* Internet Explorer 10-11 */
                color: #fff;
            }

            .controls ::-ms-input-placeholder { /* Microsoft Edge */
                color: #fff;
            }
            .g-recaptcha{
                margin-top:0;
            }
        </style>

        <div class="social">
            <div class="clear"></div>
            <ul>
                <?php if ($settings->twitter_url): ?>
                    <li><a rel="nofollow" href="{{$settings->twitter_url}}" target="_blank"><i class="icon-twitter"></i></a></li>
                <?php endif; ?>
                <?php if ($settings->facebook_url): ?>
                    <li><a rel="nofollow" href="{{$settings->facebook_url}}" target="_blank"><i class="icon-facebook"></i></a></li>
                <?php endif; ?>
                <?php if ($settings->instagram_url): ?>
                    <li><a rel="nofollow" href="{{$settings->instagram_url}}" target="_blank"><i class="icon-instagram"></i></a></li>
                <?php endif; ?>
                <?php if ($settings->pinterest_url): ?>
                    <li><a rel="nofollow" href="{{$settings->pinterest_url}}" target="_blank"><i class="icon-pinterest"></i></a></li>
                <?php endif; ?>
                <?php if ($settings->google_plus_url): ?>
                    <li><a rel="nofollow" href="{{$settings->google_plus_url}}" target="_blank"><i class="icon-gplus"></i></a></li>
                <?php endif; ?>
                <?php if ($settings->linkedin_url): ?>
                    <li><a rel="nofollow" href="{{$settings->linkedin_url}}" target="_blank"><i class="icon-linkedin"></i></a></li>
                <?php endif; ?>
                <?php if ($settings->primary_email): ?>
                    <li><a  href="mailto:{{$settings->primary_email}}"><i class="icon-envelope"></i></a></li>
                <?php endif; ?>
            </ul>
            <div class="clear"></div>
            <form
                action="{{ url('find') }}"
                method="POST"
                id="refid"
            >
                @csrf

                <div class="controls">
                    <input type="hidden" name="name" value="footerrefidsearch">

                    <input
                        type="text"
                        name="ref"
                        id="ref"
                        class="required"
                        placeholder="Ref ID:"
                        required
                        style="
                            padding: 12px 10px;
                            color: #999898;
                            font-size: 12px;
                            float: left;
                            border: none;
                            height: 30px;
                            width: 40%;
                            background: #464747;
                            text-transform: uppercase;
                            border-radius: 0;
                        "
                    >
                </div>

                <div class="col-md-7">
                    <button
                        type="submit"
                        style="
                            padding-left: 10px;
                            padding-right: 10px;
                            cursor: pointer;
                            border: none;
                            height: 30px;
                            line-height: 36px;
                            position: absolute;
                            border-radius: 0;
                            -webkit-appearance: none;
                            top: 0;
                            font-size: 12px;
                            font-family: GothamBlack;
                            color: #fff;
                            text-transform: uppercase;
                            background-color: #464747;
                            background-size: 15px;
                            width: 20%;
                        "
                    >
                        <i class="icon-search"></i>
                    </button>
                </div>
            </form>
            <div class="clear"></div>
        </div>


         <div class="row" style="padding: 0 10%">

             <a href="//www.dmca.com/Protection/Status.aspx?ID=235b958e-9df7-4154-a264-450536b9268f" title="DMCA.com Protection Status" target="_blank" class="dmca-badge"> <img data-src="https://images.dmca.com/Badges/dmca_protected_sml_120l.png?ID=235b958e-9df7-4154-a264-450536b9268f" width="121" height="24" alt="DMCA.com Protection Status" /></a>  <script src="https://images.dmca.com/Badges/DMCABadgeHelper.min.js"> </script>
        </div>

        <div class="clear"></div>
    </div>
</div>
<!-- finish footer block -->

<!-- begin copyright block -->
<div class="copyright-block">
    <div class="centering">
        <p>Copyright &copy; {{date('Y')}} <strong>Conrad Properties Co., Ltd.</strong> All rights Reserved.</p>

        <p><a href="{{url('privacy')}}">Privacy Policy</a> <a href="{{url('terms-conditions')}}">Terms &amp;
                Conditions</a> {{--<a href="#">Sitemap</a>--}}</p>

        <div class="clear"></div>
    </div>
</div>
<!-- finish copyright block -->
<div id="inset_form" style="display: none;">

</div>
