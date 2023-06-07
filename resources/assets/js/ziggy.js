    var Ziggy = {
        namedRoutes: {"debugbar.openhandler":{"uri":"_debugbar\/open","methods":["GET","HEAD"],"domain":null},"debugbar.clockwork":{"uri":"_debugbar\/clockwork\/{id}","methods":["GET","HEAD"],"domain":null},"debugbar.assets.css":{"uri":"_debugbar\/assets\/stylesheets","methods":["GET","HEAD"],"domain":null},"debugbar.assets.js":{"uri":"_debugbar\/assets\/javascript","methods":["GET","HEAD"],"domain":null},"debugbar.cache.delete":{"uri":"_debugbar\/cache\/{key}\/{tags?}","methods":["DELETE"],"domain":null},"underconstruction.check":{"uri":"check","methods":["POST"],"domain":null},"underconstruction.checkiflimited":{"uri":"checkiflimited","methods":["POST"],"domain":null},"underconstruction.index":{"uri":"locked","methods":["GET","HEAD"],"domain":null},"underconstruction.js":{"uri":"js","methods":["GET","HEAD"],"domain":null},"l5-swagger.map-v1.api":{"uri":"api\/v1\/documentation","methods":["GET","HEAD"],"domain":null},"l5-swagger.map-v1.docs":{"uri":"docs\/{jsonFile?}","methods":["GET","HEAD"],"domain":null},"l5-swagger.map-v1.asset":{"uri":"docs\/asset\/{asset}","methods":["GET","HEAD"],"domain":null},"l5-swagger.map-v1.oauth2_callback":{"uri":"api\/oauth2-callback","methods":["GET","HEAD"],"domain":null},"livewire.message":{"uri":"livewire\/message\/{name}","methods":["POST"],"domain":null},"livewire.upload-file":{"uri":"livewire\/upload-file","methods":["POST"],"domain":null},"livewire.preview-file":{"uri":"livewire\/preview-file\/{filename}","methods":["GET","HEAD"],"domain":null},"google.auth.login":{"uri":"login\/google","methods":["GET","HEAD"],"domain":null},"nova.login":{"uri":"admin\/login","methods":["POST"],"domain":null},"nova.logout":{"uri":"admin\/logout","methods":["GET","HEAD"],"domain":null},"nova.password.request":{"uri":"admin\/password\/reset","methods":["GET","HEAD"],"domain":null},"nova.password.email":{"uri":"admin\/password\/email","methods":["POST"],"domain":null},"nova.password.reset":{"uri":"admin\/password\/reset\/{token}","methods":["GET","HEAD"],"domain":null},"api.changelog":{"uri":"api\/changelog","methods":["GET","HEAD"],"domain":null},"nova.impersonate.take":{"uri":"nova-impersonate\/users\/{id}\/{guardName?}","methods":["GET","HEAD"],"domain":null},"nova.impersonate.leave":{"uri":"nova-impersonate\/leave","methods":["GET","HEAD"],"domain":null},"laravel-nova-excel.download":{"uri":"nova-vendor\/maatwebsite\/laravel-nova-excel\/download","methods":["GET","HEAD"],"domain":null},"languages.index":{"uri":"nova-vendor\/translation-manager\/languages","methods":["GET","HEAD"],"domain":null},"translation.index":{"uri":"nova-vendor\/translation-manager\/translations","methods":["GET","HEAD"],"domain":null},"translation.update":{"uri":"nova-vendor\/translation-manager\/translations","methods":["PUT"],"domain":null},"nova.index":{"uri":"admin","methods":["GET","HEAD"],"domain":null}},
        baseUrl: 'https://replog.local.statik.be/',
        baseProtocol: 'https',
        baseDomain: 'replog.local.statik.be',
        basePort: false,
        defaultParameters: []
    };

    if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
        for (var name in window.Ziggy.namedRoutes) {
            Ziggy.namedRoutes[name] = window.Ziggy.namedRoutes[name];
        }
    }

    export {
        Ziggy
    }
