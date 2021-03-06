@section('path-navigation')
<a class="breadcrumb-item" href="{{ route('panel.user.plans.list') }}">Plans</a>
<span class="breadcrumb-item active">{{ $plan->name }}</span>
@endsection

@section('custom-css')
<style>
    .bg-white {
        background-color: white;
    }
</style>
@endsection

<x-app.app-layout title="Plan Details">
    <div class="container-fluid">
        <div class="row bg-white p-4">
            <div class="col-12">
                <h1>{{ $plan->name }}</h1>
                <p>{!! $plan->shortDescription !!}</p>
                <div class="mt-3">
                    <label class="font-weight-bolder h1">
                        INR {{ $plan->price }}
                    </label>
                    <label>For {{ \App\Http\Controllers\User\PlansController::getFormattedPlanValidity($plan->plan_validity) }}</label>
                </div>

                @if (in_array($plan->id, $purchased_plans))
                    <label class="font-weight-bolder badge badge-primary">This plan is currently active</label>
                @else
                    {{-- <a href="{{ route('panel.user.plans.buy', ['plan' => $plan->id, 'paymentGateway' => 0]) }}" class="btn btn-primary">Buy now</a> --}}
                    <a href="{{ route('panel.user.plans.buy', ['plan' => $plan->id, 'paymentGateway' => 1]) }}" class="btn btn-warning">Buy now</a>
                @endif

                <div class="mt-3">
                    {!! $plan->longDescription !!}
                </div>
            </div>
        </div>
    </div>
</x-app.app-layout>