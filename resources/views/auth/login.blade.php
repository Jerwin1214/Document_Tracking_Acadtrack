<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .card-registration .select-input.form-control[readonly]:not([disabled]) {
            font-size: 1rem;
            line-height: 2.15;
            padding-left: .75em;
            padding-right: .75em;
        }

        .card-registration .select-arrow {
            top: 13px;
        }
    </style>
</head>

<body>

    <!-- Content -->
    <section class="d-flex justify-content-center align-items-center bg-dark" style="min-height: 100vh;">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-lg-8">
                    <div class="card card-registration my-4">
                        <div class="row g-0 h-100">
                            <!-- Image column -->
                            <div class="col-xl-6 d-none d-xl-block">
                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-registration/img4.webp"
                                    alt="Sample photo" class="img-fluid h-100"
                                    style="border-top-left-radius: .25rem; border-bottom-left-radius: .25rem; object-fit: cover;" />
                            </div>
                            <!-- Form column -->
                            <div class="col-xl-6">
                                <div class="card-body p-md-5 text-black h-100">
                                    <form action="/" method="post">
                                        @csrf
                                        <h3 class="mb-5 text-uppercase">Login</h3>
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="form3Example97">Email</label>
                                            <input type="text" name="email" id="form3Example97" class="form-control form-control" />
                                        </div>

                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="form3Example98">Password</label>
                                            <input type="password" name="password" id="form3Example98" class="form-control form-control" />
                                        </div>

                                        <div class="d-flex justify-content-end pt-3">
                                            <button type="submit" class="btn btn-warning btn ms-2">Login</button>
                                            <button type="button" class="btn btn-light btn">Reset</button>
                                        </div>
                                        <a href="/register">I don't have an account</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
</body>

</html>