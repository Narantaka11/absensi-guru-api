<x-admin-layout>
    <x-slot name="title">{{ __('Profile') }}</x-slot>

    <div class="row g-4">
        <div class="col-12">
            <div class="card p-4 p-md-5">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card p-4 p-md-5">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card p-4 p-md-5">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
