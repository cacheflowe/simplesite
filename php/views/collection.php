<div data-area-type="AreaHome">
  <div id="content">
    <h1>Collection</h1>
    <?php if( $pathParams == '' ) { ?>
      <p>You've reached a collection. See item <a href="/collection/one">1</a> or <a href="/collection/two">2</a></p>
    <?php } else { ?>
      <p>You've found a collection details for: <?php echo $pathParams ?></p>
    <?php } ?>
  </div>
</div>