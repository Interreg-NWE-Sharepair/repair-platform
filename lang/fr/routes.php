<?php

return [

    //GENERAL PAGES
    'home_index' => '/',
    'privacy' => 'privacy-policy',
    'cookies' => 'cookie-policy',
    'terms_conditions' => 'conditions-dutilisation',
    'device_confirmation' => '{slug}/merci',
    'contact_index' => 'contact',
    'contact_store' => 'contact-sauvegarder',
    'contact_confirmation' => 'contact/merci',
    'about' => 'a-propos-de-cette-initiative',
    'static_page' => 'page/{page}',

    //REPLOG SPECIFIC
    'device_create' => 'enregistrer-appareil/{locationCode?}/{step?}',
    'device_step_0_store' => 'sauvegarder-appareil-etappe-0',
    'device_step_1_store' => 'sauvegarder-appareil-etappe-1/{locationCode}',
    'device_step_2_store' => 'sauvegarder-appareil-etappe-2/{locationCode}',
    'repairer_login_index' => 'reparateur/se-connecter',
    'repairer_register_step_0_store_organisation_request' => 'reparateur/s\'inscrire/organisation-request',
    'repairer_register_step_0_store' => 'reparateur/s\'inscrire/sauvegarder',
    'repairer_register_confirmation' => 'reparateur/s\'inscrire/merci',
    'repairer_register_step_1_store' => 'reparateur/s\'inscrire/{locationCode?}/sauvegarder',
    'repairer_register_index' => 'reparateur/s\'inscrire/{locationCode?}',
    'repairer_dashboard' => 'reparateur/dashboard',
    'repairer_fixed_overview' => 'reparateur/appareils-repares/{locationCode?}',
    'device_show' => 'appareil/{slug}',
    'device_select_repair' => 'appareil{slug}/reparer',
    'device_confirm_repair' => 'appareil{slug}/reparer/sauvegarder',
    'device_start_repair' => 'appareil{slug}/entamer',
    'device_log_draft' => 'appareil/reparation/{uuid}/concept',
    'device_log_repaired' => 'appareil/reparation/{uuid}/completer',
    'device_log_update_device' => 'appareil/reparation/{uuid}/modifier/appareil',
    'device_log_repaired_reopen' => 'appareil/reparation/{uuid}/completer/rouvrir',
    'device_log_repaired_store' => 'appareil/reparation/{uuid}/completer/sauvegarder',
    'device_log_repaired_edit' => 'appareil/reparation/{uuid}/modifier',
    'device_log_repaired_update' => 'appareil/reparation/{uuid}/modifier/sauvegarder',
    'device_log_show' => 'appareil/reparation/{uuid}',
    'device_log_note_edit' => 'appareil/reparation/{uuid}/remarque/{id}/modifier',
    'device_log_note_add' => 'appareil/reparation/{uuid}/remarque/ajouter',
    'device_log_note_update' => 'appareil/reparation/{uuid}/remarque/{id}/modifier/sauvegarder',
    'participation' => 'participer',
    'instructions' => 'instructions-et-feedback',
    'location_show' => 'organisation/{organisation}',
    'location_search' => 'organisations/search',
    'location_index' => 'organisations',
    'location_create' => 'organisations/enregistrer',
    'location_store' => 'organisations/sauvegarder',
    'location_confirmation' => 'organisations/merci',
    'location_general_overview' => 'hersteller/{locationCode}/overzicht',
    'location_devices_overview' => 'hersteller/{locationCode}/alle-toestellen',
    'location_repairers_overview' => 'hersteller/{locationCode}/herstellers',
    'location_events_overview' => 'hersteller/{locationCode}/evenementen',
    'location_impact_overview' => 'reparateur/{locationCode}/impact',
    'location_past_events_overview' => 'hersteller/{locationCode}/evenementen/voorbij',
    'location_repairer_fixed_overview' => 'hersteller/gerepareerde-toestellen/{locationCode}',
    'history_repair_log_overview' => 'repair-history',
    'device_show_repaired' => 'toestel/hersteld/{slug}',

    'device_log_repaired_close' => 'appareil/reparation/{uuid}/update/appareil',
    'repairer_general_overview' => 'reparateur/tous-les-appareils',
    'repairer_login_store' => 'reparateur/se-connecter',
    'device_unlink_event' => 'appareil/{device:slug}/unlink',
    'person_profile_show' => 'reparateur/profile',
    'person_profile_store' => 'reparateur/profile/update',
    'repairer_profile' => 'reparateur/profile',
    'event_show' => 'evenement/{slug}',
    'device_contact_edit' => 'appareil/contact/{uuid}/editeren/sauvegarder',
    'device_note_add' => 'appareil/notities/{slug}/sauvegarder',
    'repair_log_edit_notes' => 'appareil/herstelling/{uuid}/notitie/completed/sauvegarder',
    'device_close_store' => 'appareil/{device:slug}/afsluiten',
    'device_link_event_follow_up' => 'appareil/{device:slug}/link/followup',
    'device_unlink_event_follow_up' => 'appareil/{device:slug}/unlink/followup/{detail?}',
    'device_link_follow_up_via_device' => 'appareil/{device:slug}/link/followup',


    //REPGUI SPECIFIC
    'tutorial_index' => 'tutorials',
    'tutorial_show' => 'tutoriau/{repairTutorial}',
    'tutorial_external' => 'tutorials/extern',
    'tips_index' => 'tips',
    'repair_map_index' => 'map',
    'contribute_index' => 'contribute',
    'guide_step_1' => 'guide/etappe/1',
    'guide_step_1_store' => '/guide/etappe/1/save',
    'guide_step_2' => 'guide/etappe/2',
    'guide_step_2_store' => '/guide/etappe/2/save',
    'guide_step_3' => 'guide/etappe/3',
    'guide_step_diy' => 'guide/etappe/diy',
    'guide_step_map' => 'gids/kaart/locatie',
    'about_project' => 'a-propos-de-ce-projet',
    'recycle' => 'recycler',
    'locations_show_redirect' => 'gids/kaart/redirect/{location:uuid?}',
    'locations_show' => 'map/location/{location:slug}',
    'repair_impact_calculation_index' => 'calculer-impact-reparation',
    'repair_impact_calculation_result' => 'resultat-impact-reparation',
];
