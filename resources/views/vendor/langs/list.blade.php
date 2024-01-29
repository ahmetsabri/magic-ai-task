@extends(config('amamarul-location.layout'))
@section(config('amamarul-location.content_section'))

        <div class="col-md-12">

            <h2 class="text-center mb-6">{{__('Editing Language')}}: <span class="text-3xl align-sub">{{country2flag($lang)}}</span> {{ucfirst($lang)}} </h2>

            <input type="text" id="search_string" class="form-control rounded-xl mb-6" onkeyup="searchStrings()" placeholder="{{__('Filter strings...')}}">
            <div class="card">
                <div class="card-table table-responsive">
                <table class="table" id="strings">
                    <thead>
                        <tr>
                            <th>{{__('String')}}</th>
                            <th>{{__('Translation')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $key => $value)
                        <tr>
                            <td class="hidden" width="10px"><input type="checkbox" name="ids_to_edit[]" value="{{$value->id}}" /></td>
                            @foreach ($value->toArray() as $key => $element)
                                @if ($key !== 'code')
                                    @if ( $key === 'en' )
                                        <td class="min-w-[45%]">
                                            <div data-name="{{$key}}">{{$element}}</div>
                                        </td>
                                    @else
                                        <td class="min-w-[50%]">
                                            <input
                                                class="py-2 px-2 rounded-md bg-[#F6F6F6] border-none w-full placeholder:text-gray-300"
                                                type="text"
                                                data-pk="{{$value->code}}"
                                                data-name="{{$key}}"
                                                value="{{$element}}"
                                                placeholder="{{__('enter string')}}"
                                            >
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
            </div>
            <div id="save_all" data-lang="{{$lang}}" class="btn btn-primary fixed bottom-6 w-64 left-1/2 -translate-x-1/2 hover:-translate-x-1/2 hover:-translate-y-1">Save</div>
        </div>
@endsection
@section(config('amamarul-location.scripts_section'))
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        "use strict";
        $('#save_all').click(function() {

            document.getElementById( "save_all" ).disabled = true;
            document.getElementById( "save_all" ).innerHTML = "Please Wait...";

            var formData = new FormData();
            var inputData = [];

            $('table input[type="text"]').each(function() {
                var value = $(this).val();
                inputData.push(value);
            });

            var jsonData = JSON.stringify(inputData);
            formData.append( 'data', jsonData );
            formData.append( 'lang', $(this).data('lang') );

            $.ajax( {
                type: "post",
                url: "/translations/lang/update-all",
                data: formData,
                contentType: false,
                processData: false,
                success: function ( data ) {
                    toastr.success( 'Strings saved succesfully.' );
                    document.getElementById( "save_all" ).disabled = false;
                    document.getElementById( "save_all" ).innerHTML = "Save";
                },
                error: function ( data ) {
                    var err = data.responseJSON.errors;
                    $.each( err, function ( index, value ) {
                        toastr.error( value );
                    } );
                    document.getElementById( "save_all" ).disabled = false;
                    document.getElementById( "save_all" ).innerHTML = "Save";
                }
            } );
            return false;
        });
    });

    </script>

    <script>
        function searchStrings() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("search_string");
            filter = input.value.toUpperCase();
            table = document.getElementById("strings");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                var foundMatch = false;
                td = tr[i].getElementsByTagName("td");

                if (td.length > 0) {
                    for (j = 0; j < td.length; j++) {
                        var divElement = td[j].querySelector("div[data-name='en']");
                        var inputElement = td[j].querySelector("input");
                        if (divElement && divElement.textContent.toUpperCase().indexOf(filter) > -1) {
                            foundMatch = true;
                            break;
                        } else if (inputElement && inputElement.value.toUpperCase().indexOf(filter) > -1) {
                            foundMatch = true;
                            break;
                        }
                    }
                }

                if (foundMatch) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = tr[i].parentNode.tagName === 'THEAD' ? 'table-row' : 'none';
                }
            }
        }

    </script>

@endsection
