@extends(config('amamarul-location.layout'))

@section(config('amamarul-location.content_section'))
        @include('langs::includes.tools')
        <h2 class="mt-6 mb-6 text-xl">{{__('Available Languages')}}</h2>

        <div class="row">
            @foreach ($langs as $lang)
                    <div class="card card-body mb-3 py-2 px-3 flex flex-row items-center justify-between">
                        <h4 class="text-lg flex items-center space-x-2 mb-0"><span class="text-2xl">{{country2flag($lang)}}</span> {{ucfirst($lang)}}</h4>
                        <div class="flex space-x-1">
                            <a href="{{route('amamarul.translations.lang',$lang)}}" class="btn btn-default">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4"></path><path d="M13.5 6.5l4 4"></path></svg>
                                {{__('Edit Strings')}}
                            </a>
                            <style>.dropdown-toggle::after{display:none} </style>
                            <div class="dropdown">
                                <a href="#" class="btn dropdown-toggle !border-none !shadow-none" data-bs-toggle="dropdown">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                        <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                        <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                     </svg>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="{{route('amamarul.translations.lang.generateJson',$lang)}}" class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path><path d="M14 4l0 4l-6 0l0 -4"></path></svg>
                                        <div>{{__('Generate JSON File')}}</div>
                                    </a>
                                </div>
                              </div>
                        </div>
                    </div>
            @endforeach
        </div>

@endsection