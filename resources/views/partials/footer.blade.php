        <!--Start footer-->
        <footer class="footer">
            <div class="container">
                <div class="text-center">
                    Copyright © 2019 Bulona Admin
                </div>
            </div>
        </footer>
        <!--End footer-->
        <script>
            $(document).ready(function() {
                // Add a click event listener to the logout link
                $('#logout').click(function() {
                    // Make an AJAX request to the logout endpoint
                    $.ajax({
                        type: 'POST',
                        url: '{{ route("logout") }}', // Replace with your actual logout route
                        data: {
                            _token: '{{ csrf_token() }}'
                        }, // Include CSRF token
                        success: function(response) {
                            // Redirect to the login page
                            window.location.href = '{{ route("login") }}';
                        },
                        error: function(error) {
                            console.error('Error logging out:', error);
                            // Redirect to the login page even if there's an error
                            window.location.href = '{{ route("login") }}';
                        }
                    });
                });
            });
        </script>
        </body>

        <!-- Mirrored from codervent.com/bulona/demo/pages-user-profile.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 26 Feb 2020 10:10:24 GMT -->

        </html>