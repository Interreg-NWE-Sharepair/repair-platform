<div class="flex items-center">
    <span>{{ $amount_locations }} location(s)</span>
    @if (!$amount_locations)
        <span class="w-4 h-4 text-yellow-600 flex-shrink-0 ml-1">
            <svg id="warning-icon" xmlns="http://www.w3.org/2000/svg"
                 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 20 20"
                 enable-background="new 0 0 20 20" xml:space="preserve" class="fill-current"><path
                    data-v-1591eae2="" d="M19.511,17.98L10.604,1.348C10.48,1.133,10.25,1,10,1C9.749,1,9.519,1.133,9.396,1.348L0.49,17.98
c-0.121,0.211-0.119,0.471,0.005,0.68C0.62,18.871,0.847,19,1.093,19h17.814c0.245,0,0.474-0.129,0.598-0.34
C19.629,18.451,19.631,18.191,19.511,17.98z M11,17H9v-2h2V17z M11,13.5H9V7h2V13.5z"></path></svg>
        </span>
    @endif
</div>

