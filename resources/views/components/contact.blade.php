<script src='https://www.google.com/recaptcha/api.js'></script>

<div class="container">
    <div class="row main">
        <div class="col-md-6">
            <div class="main-login main-center">
                <form class="" method="post" action="{{ route('contact') }}">
                    {!! csrf_field() !!}
                    <div class="form-group">

                        <div class="row">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user-o" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Your Name *"
                                       value="{{ old('name') }}" required=""/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope-o"
                                                                   aria-hidden="true"></i></span>
                                <input type="email" class="form-control" name="email" id="email"
                                       placeholder="Your Email *" value="{{ old('email') }}" required=""/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-phone" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" name="phone" id="phone"
                                       placeholder="Your Phone *" value="{{ old('phone') }}" required=""/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" name="subject" id="subject"
                                       placeholder="Your subject *" value="{{ old('subject') }}" required=""/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-comments-o"
                                                                   aria-hidden="true"></i></span>
                                <textarea type="text" rows="6" class="form-control" name="message" id="message"
                                          placeholder="Your message" value="{{ old('message') }}"/></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="g-recaptcha row" data-sitekey="6LdFNTcUAAAAANz4l948OCUGQz6sgxlQKyxk92gv"></div>

                    <div class="form-group ">
                        <button type="submit" class="btn btn-primary btn-lg btn-block login-button">Send</button>
                    </div>

                </form>
            </div>
        </div>
        <div class="col-md-6">
            [map]
        </div>
    </div>
</div>