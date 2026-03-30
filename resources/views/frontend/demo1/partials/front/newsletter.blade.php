<!-- <div id="subscribe" class="subscribe">
   <h4>Sign up for Newsletter</h4>
   <div class="sidemsg"></div>
   <form action="{{ url('newsletter') }}"
      method="POST"
      class="form-horizontal"
      id="sidenewfrm">

    @csrf

    <div class="control-group">
        <div class="controls">
            <input type="text"
                   name="name"
                   id="sname"
                   class="required"
                   placeholder="Your Name"
                   value="{{ old('name') }}">
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            <input type="email"
                   name="email"
                   id="semail"
                   class="required"
                   placeholder="Email Address"
                   value="{{ old('email') }}">
        </div>
    </div>

    <div class="form-actions">
        <button type="submit"
                class="btn btn-primary"
                id="newsfrm">
            SUBSCRIBE
        </button>
    </div>

</form>
</div>
 -->
