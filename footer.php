<?php if (!isset($_SESSION['user_id'])) { ?>
</div>
<?php } ?>
</div>
<footer class="site-footer">
    BWP501 - Student Registration System &copy; <?= date("Y") ?>
</footer>
<?php if (isset($_SESSION['user_id'])) { ?>
</div>
<?php } ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
