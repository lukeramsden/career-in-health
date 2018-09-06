@extends('layouts.app')
@section('content')
    <div class="container mt-lg-5">
        <div class="card card-custom w-lg-50 mx-auto">
            <div class="card-body">
                <h4 class="card-title">Customize what emails we send you</h4>
                <h5 class="card-subtitle">Check the boxes to choose what emails we send you</h5>
    
                <hr>
                
                <form action="{{ action('UserController@updateNotificationPreferences') }}"
                      method="post">
                    
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                        <p><b>Receive emails about:</b></p>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" name="email_private_message" id="email_private_message">
                            <label class="custom-control-label" for="email_private_message">Private messages you receive</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" name="email_analytics" id="email_analytics">
                            @usertype('company')
                                <label class="custom-control-label" for="email_analytics">Analytics for your addresses and listings</label>
                            @elseusertype('employee')
                                <label class="custom-control-label" for="email_analytics">Analytics for your profile</label>
                            @endusertype
                        </div>
                    </div>

                    @usertype('company')
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" value="1" name="email_listing_application" id="email_listing_application">
                                <label class="custom-control-label" for="email_listing_application">Prospective employees applying to your listings</label>
                            </div>
                        </div>
                    @endusertype
                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" name="email_promotions" id="email_promotions">
                            <label class="custom-control-label" for="email_promotions">Promotions we think might interest you</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button class="btn btn-action btn-block">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection