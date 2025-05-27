{{-- This is Allocation details --}}

<div class="row bg-light " id="#hiddenDiv1">
    {{-- Customer Details --}}
    <div class="col-12 col-md-6">
        <div class="row">
            <div class="col-8">
                <p><b class="text-black">Contact Person</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->customer->contact_person }} </p>
            </div>
            {{--  --}}
            <div class="col-8">
                <p><b class="text-black">Company Name</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->customer->company }}</p>
            </div>
            {{--  --}}
            <div class="col-8">
                <p><b class="text-black">Email </b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->customer->email }}</p>
            </div>
            {{--  --}}
            <div class="col-8">
                <p><b class="text-black">Phone Number</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->customer->phone }}</p>
            </div>
            {{--  --}}
            <div class="col-8">
                <p><b class="text-black"> Address</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->customer->address }}</p>
            </div>
            {{--  --}}
            <div class="col-8">
                <p><b class="text-black"> TIN</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->customer->TIN }}</p>
            </div>
            {{--  --}}
            <div class="col-8">
                <p><b class="text-black"> VRN</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->customer->VRN }}</p>
            </div>
            {{--  --}}
            <div class="col-8">
                <p><b class="text-black">Created Date</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->created_at }}</p>
            </div>
                 {{--  --}}
                 <div class="col-8">
                    <p><b class="text-black">Start Date</b> </p>
                </div>
                <div class="col-4 text-end">
                    <p>{{ $allocation->start_date }}</p>
                </div>
                {{--  --}}
                <div class="col-8">
                    <p><b class="text-black">End Date</b> </p>
                </div>
                <div class="col-4 text-end">
                    <p>{{ $allocation->end_date }}</p>
                </div>


        </div>
    </div>
    {{-- Trip Details --}}
    <div class="col-12 col-md-6">
        <div class="row">
            <div class="col-6">
                <p><b class="text-black">Booked Route </b> </p>
            </div>
            <div class="col-6 text-end">
                <p>{{ $allocation->route->name }}</p>
            </div>

            {{--  --}}
            <div class="col-8">
                <p><b class="text-black">Loading Point</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->loading_site }}</p>
            </div>
            {{--  --}}
            <div class="col-8">
                <p><b class="text-black">Offloading Point</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->offloading_site }}</p>
            </div>
            {{--  --}}
            <div class="col-8">
                <p><b class="text-black">Clearance</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->clearance }}
                </p>
            </div>
            {{--  --}}


            <div class="col-8">
                <p><b class="text-black">Cargo Name</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->cargo }}</p>
            </div>
            {{--  --}}
            <div class="col-8">
                <p><b class="text-black">Cargo Nature</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->nature->name }}</p>
            </div>
            {{--  --}}

            <div class="col-8">
                <p><b class="text-black">Cargo Dimensions</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->dimensions }}
                </p>
            </div>
            {{--  --}}
            <div class="col-8">
                <p><b class="text-black">Container</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->container }}
                </p>
            </div>
            {{--  --}}
            @if ($allocation->container == 'Yes')
                <div class="col-8">
                    <p><b class="text-black">Container Type</b> </p>
                </div>
                <div class="col-4 text-end">
                    <p>{{ $allocation->container_type }}
                    </p>
                </div>
            @endif

            {{--  --}}
            <div class="col-8">
                <p><b class="text-black">Cargo Quantity</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ number_format($allocation->quantity, 2) }} <small>{{ $allocation->unit }}</small>
                </p>
            </div>
            {{--  --}}
            <div class="col-8">
                <p><b class="text-black">{{ $allocation->mode->name }} Rate</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->currency->symbol }} {{ number_format($allocation->amount, 2) }}</p>
            </div>
            {{--  --}}

            <div class="col-8">
                <p><b class="text-black"> Total Trucks</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>
                    @php

                        $total_trucks = App\Models\TruckAllocation::where('allocation_id', $allocation->id)
                            ->latest()
                            ->count();
                        $planned = App\Models\TruckAllocation::where('allocation_id', $allocation->id)->sum('planned');
                        $remaining = $allocation->quantity - $planned;
                    @endphp
                    @if ($total_trucks)
                        {{ $total_trucks }}
                    @else
                        {{ $total_trucks }}
                    @endif

                </p>
            </div>
            {{--  --}}
            <div class="col-8">
                <p><b class="text-black"> Estimated Revenue</b> </p>
            </div>
            <div class="col-4 text-end">
                @php
                    $truck_income = App\Models\TruckAllocation::where('allocation_id', $allocation->id)->sum('usd_income');

                @endphp
                <p>{{ $allocation->currency->symbol }}
                    {{ number_format($truck_income, 2) }}
                </p>
            </div>
            {{--  --}}
            @php
                $truck_cost = 0;

                $trucks_allocated = App\Models\TruckAllocation::where('allocation_id', $allocation->id)->get();
                $total_allocated_trucks = App\Models\TruckAllocation::where('allocation_id', $allocation->id)->count();
                $semi = 0;
                $pulling = 0;
                foreach ($trucks_allocated as $truc) {
                    if ($truc->truck->truck_type == 1) {
                        $semi += 1;
                    } else {
                        $pulling += 1;
                    }
                    $allocated_trucks = App\Models\TruckCost::where('allocation_id', $truc->id)->sum('real_amount');
                    $truck_cost += $allocated_trucks;
                }

                $total_costs =
                    App\Models\AllocationCost::where('allocation_id', $allocation->id)
                        ->where('type', 'All')
                        ->sum('real_amount') *
                        $total_allocated_trucks +
                    $truck_cost;
                $total_semi =
                    App\Models\AllocationCost::where('allocation_id', $allocation->id)
                        ->where('type', 'Semi')
                        ->sum('real_amount') * $semi;
                $total_pulling =
                    App\Models\AllocationCost::where('allocation_id', $allocation->id)
                        ->where('type', 'Pulling')
                        ->sum('real_amount') * $pulling;
                $total_summed_cost = $total_costs + $total_semi + $total_pulling;
            @endphp
            {{-- <div class="col-8">
                <p><b class="text-black"> Estimated Cost</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->currency->symbol }}
                    @php

                        echo number_format($total_summed_cost / $allocation->currency->rate, 2);
                    @endphp

                </p>
            </div> --}}
            {{--  --}}

            @php
                $total_income = App\Models\TruckAllocation::where('allocation_id', $allocation->id)->sum('income');
                $profit = ($total_income - $total_summed_cost) / $allocation->currency->rate;
            @endphp
            {{-- <div class="col-8">
                <p><b class="text-black"> Estimated {{ $profit < 0 ? 'Loss' : 'Profit' }}</b> </p>
            </div>
            <div class="col-4 text-end">
                <p>{{ $allocation->currency->symbol }} {{ number_format($profit, 2) }}
                </p>

            </div> --}}
        </div>
    </div>

</div>
