<?php /* expects $l (array), $errors, $is_edit */ ?>
<?php foreach ($errors as $er): ?><div class="alert err"><?= e($er) ?></div><?php endforeach; ?>
<form method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <?php if ($is_edit): ?><input type="hidden" name="id" value="<?= (int)$l['id'] ?>"><?php endif; ?>
  <label>Title<input name="title" required placeholder="e.g. Sai Krupa Boys PG near College Road" value="<?= e($l['title']) ?>"></label>
  <div class="two">
    <label>Type<select name="type"><?php foreach (['PG','Hostel'] as $o): ?><option <?= $l['type']===$o?'selected':'' ?>><?= $o ?></option><?php endforeach; ?></select></label>
    <label>For<select name="gender"><?php foreach (['Boys','Girls','Any'] as $o): ?><option <?= $l['gender']===$o?'selected':'' ?>><?= $o ?></option><?php endforeach; ?></select></label>
  </div>
  <div class="two">
    <label>City<input name="city" required value="<?= e($l['city']) ?>"></label>
    <label>Area / locality<input name="area" required value="<?= e($l['area']) ?>"></label>
  </div>
  <label>Full address<input name="address" required value="<?= e($l['address']) ?>"></label>
  <label>Google Maps link (optional)
    <input name="map_link" placeholder="https://maps.app.goo.gl/..." value="<?= e($l['map_link'] ?? '') ?>">
    <small class="muted">Open Google Maps, find your property, tap Share, then Copy link and paste it here.</small>
  </label>
  <div class="two">
    <label>Rent per month (₹)<input type="number" name="rent" min="0" required value="<?= e($l['rent']) ?>"></label>
    <label>Deposit (₹)<input type="number" name="deposit" min="0" value="<?= e($l['deposit']) ?>"></label>
  </div>
  <div class="two">
    <label>Sharing (people per room)<input type="number" name="sharing" min="1" max="10" required value="<?= e($l['sharing']) ?>"></label>
    <label>Contact phone (10 digits)<input name="contact_phone" maxlength="10" required value="<?= e($l['contact_phone']) ?>"></label>
  </div>
  <label>Amenities (comma separated)<input name="amenities" placeholder="WiFi, Meals, Laundry, AC, Parking" value="<?= e($l['amenities']) ?>"></label>
  <label>Description<textarea name="description" rows="5"><?= e($l['description']) ?></textarea></label>
  <label>Photo (JPG/PNG, max 2 MB)<input type="file" name="image" accept="image/jpeg,image/png"></label>
  <?php if ($is_edit): ?>
    <label class="check"><input type="checkbox" name="is_available" <?= $l['is_available'] ? 'checked' : '' ?>> Rooms are available</label>
  <?php endif; ?>
  <button class="btn"><?= $is_edit ? 'Save changes' : 'Publish listing' ?></button>
</form>
