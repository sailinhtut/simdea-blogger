@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm p-4">
                    <h2 class="mb-4 text-center text-indigo-600 font-bold">Reset Password</h2>
                    <form id="reset-password-form" novalidate>
                        <input type="hidden" id="token" name="token" />
                        <input type="hidden" id="email" name="email" />

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" required
                                minlength="6" />
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                class="form-control" required minlength="6" />
                            <div class="invalid-feedback"></div>
                        </div>

                        <button type="submit" class="bg-indigo-600 text-white w-100 py-2 rounded shadow-sm">
                            Reset Password
                        </button>
                        <div id="form-message" class="mt-3 text-center"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('reset-password-form');
            const messageEl = document.getElementById('form-message');

            // Parse token and email from URL
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token') || '';
            const email = urlParams.get('email') || '';

            // Inject to hidden inputs
            form.token.value = token;
            form.email.value = decodeURIComponent(email);

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                messageEl.textContent = '';
                messageEl.className = 'mt-3 text-center';

                form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                const newPassword = form.new_password.value;
                const confirmPassword = form.new_password_confirmation.value;

                let hasError = false;

                if (newPassword.length < 6) {
                    form.new_password.classList.add('is-invalid');
                    form.new_password.nextElementSibling.textContent =
                        'Password must be at least 6 characters.';
                    hasError = true;
                }

                if (confirmPassword !== newPassword) {
                    form.new_password_confirmation.classList.add('is-invalid');
                    form.new_password_confirmation.nextElementSibling.textContent =
                        'Passwords do not match.';
                    hasError = true;
                }

                if (!token || !email) {
                    messageEl.textContent = 'Invalid password reset link.';
                    messageEl.classList.add('text-danger', 'fw-semibold');
                    return;
                }

                if (hasError) return;

                try {
                    const res = await fetch('/api/reset-password', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            // Optionally add token here if your API requires Authorization
                            // 'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                        },
                        body: JSON.stringify({
                            token: token,
                            email: email,
                            new_password: newPassword,
                            new_password_confirmation: confirmPassword
                        }),
                    });

                    const data = await res.json();

                    if (!res.ok) throw new Error(data.message || 'Failed to reset password.');

                    messageEl.textContent = data.message ||
                        'Password reset successfully! Redirecting to login...';
                    messageEl.classList.add('text-success', 'fw-semibold');

                    form.reset();

                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);

                } catch (error) {
                    messageEl.textContent = error.message;
                    messageEl.classList.add('text-danger', 'fw-semibold');
                }
            });
        });
    </script>
@endpush
