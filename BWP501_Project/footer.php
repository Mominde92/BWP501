<?php if (isset($_SESSION['user_id'])) { ?>
    </div>
    <footer class="app-footer">
        BWP501 Project &middot; Student Registration System &copy; <?= date("Y") ?>
    </footer>
</div>
<?php } else { ?>
</div>
<?php } ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
