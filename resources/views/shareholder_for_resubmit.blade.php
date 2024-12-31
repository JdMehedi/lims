<div style="font-family: Arial, sans-serif; line-height: 1.4; margin: -22px 0 20px 58px; max-width: 800px; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <!-- Logo Section -->
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="/assets/rjsc/rjsc.png" alt="RJSC Logo" style="max-width: 150px; height: auto;"/>
    </div>
    <h2 style="text-align: center; color: #2C3F66; font-weight: bold; margin-bottom: 10px;">
        Office of the Registrar of Joint Stock Companies and Firms
    </h2>
    <br>
    <br>
    {{--        entity_master             --}}
    <h2 class="text-center" style="font-family: 'initial'">Entity details</h2>
    <hr>
    @if(empty($entity))
        <p style="text-align: center; color: #34495e; margin: 5px 0;"> There is {{ $error }}</p>
    @endif
    @if(empty($error))

        @if(!empty($entity['entity_master']['entity_name']))
            <p style="text-align: center; color: #34495e; margin: 5px 0;">
                Name: {{ $entity['entity_master']['entity_name'] }}
            </p>
        @endif
        @if(!empty($entity['entity_master']['entity_type']))
            <p style="text-align: center; color: #34495e; margin: 5px 0;">
                Type: {{ $entity['entity_master']['entity_type'] }}
            </p>
        @endif
        @if(!empty($entity['entity_master']['entity_address']))
            <p style="text-align: center; color: #34495e; margin: 5px 0;">
                Address: {{ $entity['entity_master']['entity_address'] }}
            </p>
        @endif

        <br>
        <br>
        <br>
        {{--        persons             --}}
        <h2 class="text-center" style="font-family: 'initial'">List of Shareholder/Director</h2>
        @foreach ($entity['persons'] as $person)
            @if(!empty($person['person_name']))
                <p style="text-align: center; color: #34495e; margin: 5px 0;">
                    Name: {{ $person['person_name'] }}
                </p>
            @endif
            @if(!empty($person['person_tin']))
                <p style="text-align: center; color: #34495e; margin: 5px 0;">
                    TIN: {{ $person['person_tin'] }}
                </p>
            @endif
            @if(!empty($person['person_country']))
                <p style="text-align: center; color: #34495e; margin: 5px 0;">
                    Country: {{ $person['person_country'] }}
                </p>
            @endif
            <br>
            {{--        </li>--}}
        @endforeach
        <br>
    @endif
    <!-- Close button -->
    <span id="verify" class="btn btn-primary close" onclick="closeModal()">Close</span>
</div>
