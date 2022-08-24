<!DOCTYPE html>

<html lang="en">
	<!--begin::Head-->
	<head><base href="">
		<meta charset="utf-8" />
		<title>teraMedik Cloud</title>
		<meta name="description" content="Metronic admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services and widgets." />
		<meta name="keywords" content="Metronic, bootstrap, bootstrap 5, Angular 11, VueJs, React, Laravel, admin themes, web design, figma, web development, ree admin themes, bootstrap admin, bootstrap dashboard" />
		<link rel="canonical" href="Https://preview.keenthemes.com/metronic8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/kiosk-cl.css') }}" rel="stylesheet" type="text/css" />

		<style type="text/css">
			#logo-footer {
				display: block;
				position: fixed;
				right: 0;
				bottom:  0;
				z-index: -1;
			}
		</style>

		@yield('header')
		<!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed toolbar-tablet-and-mobile-fixed bg-light" style="--kt-toolbar-height:100px;--kt-toolbar-height-tablet-and-mobile:100px;background: url({{  asset('assets/images/kiosk-bg2.jpg') }}) bottom right no-repeat; background-size: cover; background-attachment: fixed;">
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">

				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">

					<!--begin::Header-->
					<div id="kt_header" class="header align-items-stretch shadow-0 border-0 bg-grey">
						<!--begin::Container-->
						<div class="container-fluid d-flex align-items-center justify-content-between position-relative">
							<!--begin::Mobile logo-->
							<div class="d-flex align-items-center">
								<a href="index.html" class="d-lg-none">
									<img alt="Logo" src="{{  asset('assets/media/logo-kiosk.png') }}" class="h-30px" />
								</a>

								<a href="{{ route('home') }}" class="position-relative" style="left: 3.5rem;">
									<i class="fas fa-home text-k-primary" style="font-size: 3rem"></i>
								</a>
							</div>
							<!--end::Mobile logo-->

							{{-- Logo RS --}}
							<div class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%)">
								<img class="img-fluid" src="{{ asset('assets/images/logorsdemo-01.png') }}" alt="Logo RS">
							</div>

							<!--begin::Wrapper-->
							<div class="d-flex align-items-stretch justify-content-between">
								<!--begin::Navbar-->
								<div class="d-flex align-items-center" id="kt_header_nav">
									<!--begin::Page title-->
									<div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_header_nav'}" class="page-title d-flex flex-column align-items-start me-3 flex-wrap mb-5 mb-lg-0 lh-1">

									</div>
									<!--end::Page title-->

									<div class="py-5 text-center d-none d-lg-block mt-5">
										{{-- <img alt="Logo" src="{{ asset('assets/media/logo-kiosk.png') }}" class="h-55px" /> --}}
									</div>

								</div>
								<!--end::Navbar-->

								<!--begin::Topbar-->
								<div class="d-flex align-items-stretch flex-shrink-0">
									<!--begin::Toolbar wrapper-->
									<div class="text-end fw-bolder text-k-primary py-3 lh-1 me-3">
										<span class="d-block fs-3 mb-1" id="clock">12:00</span>
										<span class="d-block fs-7" id="date">{{ $_today }}</span>
									</div>
									<!--end::Toolbar wrapper-->

									<button type="button" class="btn btn-sm p-2" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="bottom-end">
										<span class="svg-icon svg-icon-2">
											<img src="{{ asset('assets/media/flags/indonesia.svg') }}" class="h-30px rounded-circle mt-n2" alt="English">
										</span>
									</button>
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded rounded-1 menu-gray-600 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3 rounded-1">
												<img src="{{ asset('assets/media/flags/indonesia.svg') }}" class="h-15px me-3" alt="Bahasa Indonesia">
												BAHASA INDONESIA
											</a>
										</div>
										<div class="separator my-3"></div>
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3 rounded-1">
												<img src="{{ asset('assets/media/flags/uk.svg') }}" class="h-15px me-3" alt="English">
												ENGLISH
											</a>
										</div>
									</div>									
								</div>
								<!--end::Topbar-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Header-->


					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid bg-transparent py-10" id="kt_content">
						<!--begin::Toolbar-->
						<div class="toolbar bg-grey border-0 shadow-0" id="kt_toolbar">
							<!--begin::Container-->
							<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">

								<div class="d-flex flex-row w-100">
									@isset($_backUrl)
								    <div class="d-flex flex-column flex-row-auto">
								        <div class="d-flex flex-column-auto">
								        	<a href="{{ $_backUrl === 'back' ? '#' : $_backUrl }}" id="back" class="btn btn-md btn-white border-1 border-primary rounded-1 bg-grad-blue text-white">
												<i class="las la-chevron-left text-white fs-3"></i> Kembali
											</a>
								        </div>
								    </div>
								    @endisset

								    <div class="d-flex flex-column flex-row-fluid">
								        <div class="text-center">
								            <span class="fs-1 fw-bolder text-k-primary">{{ @$_title }}</span>
								            <span class="d-block fs-5 text-dark">{{ @$_subtitle }}</span>
								        </div>
								    </div>

								    <div class="d-flex flex-column flex-row-auto w-50px">
								        <div class="d-flex flex-column-auto">
								        	<!-- Button -->
								        </div>
								    </div>
								</div>

							</div>
							<!--end::Container-->
						</div>
						<!--end::Toolbar-->

						<!--begin::Post-->
						<div class="post d-flex flex-column-fluid" id="kt_post">
							<!--begin::Container-->
							<div id="kt_content_container" class="container-fluid px-10">

								<!--begin:content-->
								@yield('content')
							
								<!--end::content-->
								
							</div>
							<!--end::Container-->
						</div>
						<!--end::Post-->
					</div>
					<!--end::Content-->

					<!--begin::Footer-->
					<div class="footer py-4 d-flex flex-lg-column d-none" id="kt_footer">
						<!--begin::Container-->
						<div class="container-fluid d-flex flex-column flex-md-row align-items-center">
							<!--begin::Copyright-->

							<div class="text-dark order-2 order-md-1 text-center w-100">
								<span class="text-muted fw-bold me-1">2022©</span>
								<a href="" target="_blank" class="text-gray-800 text-hover-primary">Terakorp Indonesia, PT.</a>
							</div>
							<!--end::Copyright-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Footer-->

				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Root-->

		<!--begin::Drawers-->
		<!--end::Drawers-->

		<!-- Logo Footer -->
		<div id="logo-footer">
			<img src="{{ asset('assets/images/logotera-01.png') }}" width="200" alt="Logo Footer">
		</div>

		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<!--begin::Svg Icon | path: icons/duotone/Navigation/Up-2.svg-->
			<span class="svg-icon">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<polygon points="0 0 24 0 24 24 0 24" />
						<rect fill="#000000" opacity="0.5" x="11" y="10" width="2" height="10" rx="1" />
						<path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
					</g>
				</svg>
			</span>
			<!--end::Svg Icon-->
		</div>
		<!--end::Scrolltop-->
		<!--end::Main-->
		<!--begin::Javascript-->
		<!--begin::Global Javascript Bundle(used by all pages)-->
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Page Custom Javascript(used by this page)-->
		<script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
		<script type="text/javascript" src="{{ asset('assets/js/index.js') }}"></script>
		<script>
			@if (Request::url() !== route('home') )
				setTimeout(redirectHome, {{ $_timeout }});
			@endif

			function redirectHome() {
				window.location = '{{ route('home') }}'
			}
		</script>	
		@yield('foot')
		<!--end::Page Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>