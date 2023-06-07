@extends('statikbe::mail.designs.default.mail')

@section('content')
    <!-- START CENTERED WHITE CONTAINER -->
    <table role="presentation" class="main">
        <!-- START MAIN CONTENT AREA -->
        <tr>
            <td class="wrapper">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="100%" height="10"></td>
                    </tr>
                    <tr>
                        <td>
                            <h2>{{ trans('mail.contact_title') }}</h2>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" height="25"></td>
                    </tr>
                    <tr>
                        <td>
                            {{trans('mail.name')}}: <strong>{{ $contact->name }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{trans('mail.email_short')}}: <strong>{{ $contact->email }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{trans('mail.message')}}: <strong>{{ $contact->message }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" height="25"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- END MAIN CONTENT AREA -->
    </table>
    <!-- END CENTERED WHITE CONTAINER -->
@endsection

@section('footer')
    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
        {{--<tr>
            <td class="content-block">
                <span class="apple-link">Company Inc, 3 Abbey Road, San Francisco CA 94102</span>
                <br> Don't like these emails? <a href="#">Unsubscribe</a>.
            </td>
        </tr>--}}
        <tr>
            <td class="content-block powered-by">
                Powered by <a href="https://github.com/statikbe/laravel-mail-template-engine">Laravel Mail Template Engine</a>.
            </td>
        </tr>
    </table>
@endsection
