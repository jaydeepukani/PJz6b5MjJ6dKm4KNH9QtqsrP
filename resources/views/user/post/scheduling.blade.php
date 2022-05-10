@section('path-navigation')
<a class="breadcrumb-item" href="#">Posts</a>
<span class="breadcrumb-item active">Post Management</span>
@endsection

@section('custom-scripts')
<script>
    $(document).ready(function() {
        $(".tagsSelection").select2('destroy').val("");
        $(".tagsSelection").select2({
            tags: true,
            tokenSeparators: [',', ' '],
            minimumInputLength: 2,
            maximumInputLength: 255,
            multiple: true,
            placeholder: 'Enter your tags or select tags from suggestions',
            initSelection: function(element, callback) {},
            ajax: {
                url: "{{ route('tag.suggest') }}",
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function(data) {
                    return {
                        '_token': '{{ csrf_token() }}',
                        'search': data ?? ''
                    };
                },
                processResults: function(response) {
                    return {
                        results: $.map(response, function(item) {
                            return {
                                id: item.tag,
                                text: item.tag
                            }
                        })
                    };
                },
                cache: false
            }
        }).on('change', function(e) {
            $.ajax({
                url: "{{ route('tag.add') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'tag': e.added.text
                },
                processResults: function(response) {
                    return {
                        results: $.map(response, function(item) {
                            return {
                                id: item._id,
                                text: item.tag
                            }
                        })
                    };
                }
            });
        });

        $('#datetimepicker1').datetimepicker();
        $('.js-example-basic-single').select2();

        $('#profile_select').change(function() {
            var selected_profile = $('#profile_select').find(':selected').val();
            $.ajax({
                data: {
                    _token: '{{ csrf_token() }}',
                    profile_key: selected_profile,
                },
                dataType: 'json',
                success: function(response) {
                    let data = response;
                    if (data.length > 0) {

                        if (data.includes('instagram')) {
                            $('#cbinstagram').removeAttr('disabled');
                        }
                        if (data.includes('facebook')) {
                            $('#cbfacebook').removeAttr('disabled');
                        }
                        if (data.includes('linkedin')) {
                            $('#cblinkdin').removeAttr('disabled');
                        }
                        if (data.includes('twitter')) {
                            $('#cbtwitter').removeAttr('disabled');
                        }
                        if (data.includes('youtube')) {
                            $('#cbyoutube').removeAttr('disabled');
                        }
                        if (data.includes('pinterest')) {
                            $('#cbpintrest').removeAttr('disabled');
                        }

                    }
                    if (data.length == 0) {
                        $('#cbinstagram').attr('disabled', true);
                        $('#cbfacebook').attr('disabled', true);
                        $('#cblinkdin').attr('disabled', true);
                        $('#cbtwitter').attr('disabled', true);
                        $('#cbyoutube').attr('disabled', true);
                        $('#cbpintrest').attr('disabled', true);
                    }
                }
            });
        });

    });
</script>
@endsection

<x-app.app-layout title="Post Scheduler">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header p-15 ml-3 w-500">
                <h4 class="h3 m-0">{{ __('Post Details') }}</h4>
                <span>To know more about platform requirements <a href="" data-toggle="modal" data-target="#postStandards">click here</a>.</span>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" action="{{ route('panel.user.post.upload_media') }}">
                    @csrf

                    @if(session()->get('message2'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success:</strong> {{ session()->get('message2') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    <div class="form-group col-12">
                        <select id="profile_select" name="profile_select" class="form-control col-12 js-example-disabled-results">
                            <option selected disabled="disabled"> Please Select Profile To Post </option>
                            @foreach($userProfiles as $key => $profiles)
                            <option value="{{ encrypt($profiles['profile_key']) }}">{{ $profiles['title'] }}</option>
                            @endforeach
                        </select>
                        @error('profile_select')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-12">
                        <label class="font-weight-semibold" for="platfromSelection">{{ __('Select Social Media Platform(s)') }}:<sup>*</sup></label>
                        <div class="checkbox row">
                            <div class="col-md-2">
                                <input id="cbinstagram" name="platform[]" value="{{ App\Helper\TokenHelper::$PLATFORMS['instagram'] }}" disabled type="checkbox">
                                <label for="cbinstagram">Instagram</label>
                            </div>
                            <div class="col-md-2">
                                <input id="cbyoutube" name="platform[]" value="{{ App\Helper\TokenHelper::$PLATFORMS['youtube'] }}" disabled type="checkbox">
                                <label for="cbyoutube">YouTube</label>
                            </div>

                            <div class="col-md-2">
                                <input id="cbfacebook" name="platform[]" value="{{ App\Helper\TokenHelper::$PLATFORMS['facebook'] }}" disabled type="checkbox">
                                <label for="cbfacebook">Facebook</label>
                            </div>

                            <div class="col-md-2">
                                <input id="cbtwitter" name="platform[]" value="{{ App\Helper\TokenHelper::$PLATFORMS['twitter'] }}" disabled type="checkbox">
                                <label for="cbtwitter">Twitter</label>
                            </div>

                            <div class="col-md-2">
                                <input id="cblinkdin" name="platform[]" value="{{ App\Helper\TokenHelper::$PLATFORMS['linkedin'] }}" disabled type="checkbox">
                                <label for="cblinkdin">linkedIn</label>
                            </div>

                            <div class="col-md-2">
                                <input id="cbpintrest" name="platform[]" value="{{ App\Helper\TokenHelper::$PLATFORMS['pintrest'] }}" disabled type="checkbox">
                                <label for="cbpintrest">Pintrest</label>
                            </div>
                        </div>
                        @error('platform')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-12">
                        <label class="font-weight-semibold" for="caption">{{ __('Caption') }}:<sup>*</sup></label>
                        <input type="text" class="form-control" id="caption" name="caption" placeholder="Enter a caption here..">
                        @error('caption')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-12">
                        <label class="font-weight-semibold" for="mention">{{ __('Mention Users') }}:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">@</span>
                            </div>
                            <input type="text" class="form-control" id="mention" name="mention" placeholder="Enter Username to mention seperated by ',' comma " />
                        </div>
                        @error('mention')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-12">
                        <label class="font-weight-semibold" for="tags">{{ __('Tags') }}:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">#</span>
                            </div>
                            <input type="text" class="tagsSelection" style="flex : 1 1 auto; border: 0px;" id="tags" name="tags" />
                        </div>
                        @error('tags')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-12">
                        <label class="font-weight-semibold" for="scheduleAt">{{ __('Schedule Post') }}:</label>
                        <input type="datetime-local" class="form-control" id="scheduleAt" name="scheduleAt" placeholder="Select Date & Time">
                        @error('scheduleAt')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-12">
                        <label class="font-weight-semibold" for="postedBy">{{ __('Posted By') }}:<sup>*</sup></label>
                        <input type="text" class="form-control" id="postedBy" name="postedBy" placeholder="Enter Your Full Name">
                        @error('postedBy')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-12">
                        <h5 class="m-b-5 font-size-18">{{ __('Choose Post Image') }}</h5>
                        <p class="opacity-07 font-size-13 m-b-0 media">
                            <input id="post_media" name="post_media" type="file" class="hidden" accept="image/png, image/jpeg, image/jpg">
                            {{ __('Recommended Resolution') }}: 1080 x 1350p<br>
                            Our system will automatically manage resolutions for Facebook & Instagram.
                            Max file size: 6MB
                        </p>
                        @error('post_media')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-12">
                        <button id="btnUploadPostMedia" class="btn btn-tone btn-lg btn-primary btn-block">{{ __('Upload') }}</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" id="postStandards" tabindex="-1" role="dialog" aria-labelledby="addAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Media Standards</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-borderless" data-toggle="table" >
                            <caption></caption>
                            <thead>
                                <tr>
                                    <th scope="col" rowspan="2">#</th>
                                    <th scope="col" rowspan="2" data-field="Platform">Platform</th>
                                    <th scope="col" rowspan="2" data-field="supported_type">Supported Media Type</th>
                                    <th scope="col" colspan="2">Max Size Supported</th>
                                </tr>
                                <tr>
                                    <th scope="col" data-field="images_size">Image</th>
                                    <th scope="col" data-field="video_size">Video</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Facebook</td>
                                    <td>JPG,BMP,PNG,GIF,TIFF,MP4,MOV,AVI</td>
                                    <td>10 MB</td>
                                    <td>3 GB</td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>Instagram</td>
                                    <td>JPG,PNG,MP4,MOV</td>
                                    <td>8 MB</td>
                                    <td>100 MB</td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>LinkedIn</td>
                                    <td>JPG,PNG,GIF,ASF,AVI,FLV,MP4,MOV,MKV,WEBM</td>
                                    <td>5 MB</td>
                                    <td>10 GB</td>
                                </tr>
                                <tr>
                                    <th scope="row">4</th>
                                    <td>Twitter</td>
                                    <td>JPG,PNG,GIF,WEBP,MP4,MOV</td>
                                    <td>5 MB</td>
                                    <td>512 MB</td>
                                </tr>
                                <tr>
                                    <th scope="row">5</th>
                                    <td>YouTube</td>
                                    <td>MP4,MOV</td>
                                    <td>N/A</td>
                                    <td>5 GB</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app.app-layout>