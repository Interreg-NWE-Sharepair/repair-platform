@extends('repgui.layouts.layout')
@section('content_body')

<div id="repguiMap">
  <div class="pb-2 section section--light">
    <div class="container">
      <h1 class="text-secondary">{{ trans('repgui.repair_map_title') }}</h1>
      <repair-map-filters @change="toggleFilter($event)" :active-filter="activeFilter" type-text="{{ trans('repgui.filter.type') }}" category-text="{{ trans('repgui.filter.category') }}"></repair-map-filters>
    </div>
  </div>
  <repgui-map baseUrl="{{ route('locations_show_redirect') }}" :active-filter="activeFilter" @close-filter="toggleFilter($event)" :filters="{organisation_types: ['repair_cafe']}" locale="{{ Lang::locale() }}"></repgui-map>
</div>
@endsection

<script src="{{ URL::asset('repgui/js/map.js') }}"></script>
