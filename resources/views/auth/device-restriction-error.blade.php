@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-components.css') }}">
@endsection

<x-app-layout>
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <!-- Navigation can go here if needed -->
            </div>
        </div>
    </div>
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-6 col-md-8">
                            <div class="card card-plain mt-8">
                                <div class="card-header pb-0 text-center bg-transparent">
                                    <div class="mb-4">
                                        @if(session('error_type') === 'ip_limit_reached')
                                            <i class="fas fa-globe text-warning icon-4rem"></i>
                                        @else
                                            <i class="fas fa-shield-alt text-danger icon-4rem"></i>
                                        @endif
                                    </div>
                                    <h3 class="font-weight-bold text-dark">{{ session('error', 'Access Restricted') }}</h3>
                                </div>
                                
                                <div class="card-body">
                                    @if(session('error_details'))
                                        <div class="alert alert-info">
                                            <p class="mb-0">{{ session('error_details') }}</p>
                                        </div>
                                    @endif

                                    @if(session('error_type') === 'ip_limit_reached')
                                        <div class="mt-4">
                                            <h5 class="text-dark">What can you do?</h5>
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    Login from one of your previously used internet connections
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    Contact support to reset your IP address limit
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-info-circle text-info me-2"></i>
                                                    This limit prevents account sharing and ensures security
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-mobile-alt text-primary me-2"></i>
                                                    You can use any device type (Desktop, Mobile, Tablet) from your allowed locations
                                                </li>
                                            </ul>
                                        </div>
                                    @endif

                                    @if(session('support_contact'))
                                        <div class="mt-4 p-3 bg-light rounded">
                                            <h6 class="text-dark mb-2">
                                                <i class="fas fa-headset me-2"></i>
                                                Need Help?
                                            </h6>
                                            <p class="mb-2 text-sm">
                                                Our support team is here to help you resolve this issue quickly.
                                            </p>
                                            <a href="mailto:{{ session('support_contact') }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-envelope me-1"></i>
                                                Contact Support
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('sign-in') }}" class="btn btn-outline-dark">
                                            <i class="fas fa-arrow-left me-1"></i>
                                            Try Again
                                        </a>
                                        <a href="{{ route('dashboard') }}" class="btn btn-dark">
                                            <i class="fas fa-home me-1"></i>
                                            Go to Homepage
                                        </a>
                                    </div>
                                    <p class="mb-0 text-xs mx-auto mt-3">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        These security measures protect your account and learning progress.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>
