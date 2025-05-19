<?php include 'header.php'; ?>

<div id="wrapper">
    <?php include 'sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <?php include 'navbar.php'; ?>

        <div id="content">
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Available Services & Add New Event</h1>
                    </div>

                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Add New Event</h6>
                                </div>
                                <div class="card-body">
                                    <form action="process_add_event.php" method="POST">
                                        <div class="form-group">
                                            <label for="occasion">Occasion:</label>
                                            <input type="text" class="form-control" id="occasion" name="occasion" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="venue">Venue:</label>
                                            <input type="text" class="form-control" id="venue" name="venue">
                                        </div>
                                        <div class="form-group">
                                            <label for="location_address">Location Address:</label>
                                            <input type="text" class="form-control" id="location_address" name="location_address">
                                        </div>
                                        <div class="form-group">
                                            <label for="location_url">Google Maps URL:</label>
                                            <input type="url" class="form-control" id="location_url" name="location_url">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone:</label>
                                            <input type="text" class="form-control" id="phone" name="phone">
                                        </div>
                                        <div class="form-group">
                                            <label for="website_text">Website Text (Display):</label>
                                            <input type="text" class="form-control" id="website_text" name="website_text">
                                        </div>
                                        <div class="form-group">
                                            <label for="website_url">Website URL:</label>
                                            <input type="url" class="form-control" id="website_url" name="website_url">
                                        </div>
                                        <div class="form-group">
                                            <label for="rating">Rating:</label>
                                            <input type="number" class="form-control" id="rating" name="rating" step="0.01" min="0" max="5">
                                        </div>
                                        <div class="form-group">
                                            <label for="event_date">Event Date:</label>
                                            <input type="date" class="form-control" id="event_date" name="event_date">
                                        </div>
                                        <div class="form-group">
                                            <label for="about_event">About Event:</label>
                                            <textarea class="form-control" id="about_event" name="about_event" rows="4"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="about_venue">About Venue:</label>
                                            <textarea class="form-control" id="about_venue" name="about_venue" rows="4"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add Event</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row row-custom no-gutters col-12">
                                <?php include '../../db.connection/db_connection.php'; ?>

                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'footer.php'; ?>
    </div>
</div>

<?php include 'end.php'; ?>