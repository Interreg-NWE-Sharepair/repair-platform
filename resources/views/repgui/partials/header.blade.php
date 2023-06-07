<header class="page-header" >
    <div class="hidden bg-light md:block">
        <div class="container">
            <div class="relative flex items-center justify-end py-2 text-base">
                <div class="flex items-center">
                    @include('repgui.partials.menu.header')
                    @include('repgui.partials.menu.language')
                </div>
            </div>
        </div>
    </div>
    <div class="py-6">
        <div class="container">
            @include('repgui.partials.menu.main')
        </div>
    </div>
</header>

{{--@include('partials.messages')--}}
