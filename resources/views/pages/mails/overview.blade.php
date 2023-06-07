@extends('layouts.base')

@section('content_body')
    <h1>Overzicht mail templates</h1>
    <ol>
        <li><a href="/mails/1">sendContactMail</a><br></li>
        <li><a href="/mails/2">sendDeviceRegisteredMail</a><br></li>
        <li><a href="/mails/3">sendMailSelectedForRepair</a><br></li>
        <li><a href="/mails/4">sendDeviceMailSelectedForRepair (now also included in sendMailSelectedForRepair)</a><br></li>
        <li><a href="/mails/5">sendOpenDevicesToRepairer</a><br></li>
        <li><a href="/mails/6">sendDeviceReminderToRepairer</a><br></li>
        <li><a href="/mails/7">sendOrganisationSuggestionMail</a><br></li>
        <li><a href="/mails/8">sendRepairerRegisteredMail</a><br></li>
        <li><a href="/mails/9">sendRepairerRegisteredAdminMail</a><br></li>
        <li><a href="/mails/10">sendAccountActivatedMail</a><br></li>
        <li><a href="/mails/11">sendDeviceReopenedMail</a><br></li>
        <li><a href="/mails/12">sendDeviceFixedMail</a><br></li>
        <li><a href="/mails/13">sendMailRegisteredEvent</a><br></li>
        <li><a href="/mails/14">sendMailUnlinkedEvent</a><br></li>
        <li><a href="/mails/15">sendMailLinkedEvent</a><br></li>
    </ol>
    <br>
    <p>Edit the URL lang parameter to change language</p>
    <p>When receiving an error, refresh te page a couple times.</p>
    <br>
@endsection

