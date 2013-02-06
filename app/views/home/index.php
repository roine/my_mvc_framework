<h1>Welcome Home Bro</h1>



<div class="row">
	<div class="three columns">
		<div class="panel">
			This is a 12 columns responsive grid
	</div>
</div>
<div class="three columns">
	<div class="panel">
		<?php print_r($first); ?>
	</div>
</div>
<div class="six columns end">
	<div class="panel">
		<a href="#" class="button success round">success rounded</a>
		<a href="#" class="button alert">alert</a>
		<ul class="button-group radius even five-up">
			<li><a href="#" class="button radius">1</a></li>
			<li><a href="#" class="button radius">2</a></li>
			<li><a href="#" class="button radius">3</a></li>
			<li><a href="#" class="button radius">4</a></li>
			<li><a href="#" class="button radius">5</a></li>
		</ul>
	</div>
</div>
</div>
<div class="row">
	<div class="twelve columns">
		<div class="panel">
			<input type="radio" class="radio">
			<input type="text" placeholder="this is a placeholder">
			<input type="text" placeholder="this is an other placeholder">
			<div class="row">
				<div class="six columns">
					<input type="text" placeholder="this is not a placeholder">
				</div>
				<div class="six columns">
					<input type="text" placeholder="this is neither a placeholder">
				</div>
				
			</div>
			
		</div>
	</div>
</div>
<div class="row">
	<div class="four columns">
			<p class="panel">The following text should describe the device you are using:
				<strong class="show-for-xlarge">You are on a very large screen.</strong>
				<strong class="show-for-large">You are on a large screen.</strong>
				<strong class="show-for-large-up">You are on a large or very large screen.</strong>
				<strong class="show-for-medium">You are on a medium screen.</strong>
				<strong class="show-for-medium-down">You are on a medium or small screen.</strong>
				<strong class="show-for-small">You are on a small screen, like a smartphone.</strong>
			</p>
	</div>
	<div class="four columns">
		<p class="panel">The following text should describe the device you are using:
			<strong class="show-for-touch">You are on a touch-enabled device.</strong>
			<strong class="hide-for-touch">You are not on a touch-enabled device.</strong>
		</p>
	</div>
	<div class="four columns">

		<p class="panel">The following text should describe the device you are using:
			<strong class="show-for-landscape">You are in landscape orientation.</strong>
			<strong class="show-for-portrait">You are in portrait orientation.</strong>
		</p>

	</div>

</div>
<div class="row">
	<div class="two columns">
		<div class="panel">
			<div class="alert-box">alert box</div>
		</div>
	</div>
	<div class="two columns">
		<div class="panel">
			<span class="label success">success</span>
		</div>
	</div>
	<div class="two columns">
		<div class="panel">
			<span class="label round">1,000.1</span>
		</div>
	</div>
	<div class="two columns">
		<div class="panel callout radius">
			<span class="label success">message</span>
		</div>
	</div>
	<div class="two columns">
		<div class="panel">
			<input type="checkbox" class='switchMeter'>Add 50 % with transition
			<div class="progress twelve"><span class="meter"></span></div>
			<style>
				.meter{
					-webkit-transition: all 2s linear;
						-moz-transition: all 2s linear;
						-ms-transition: all 2s linear;
						-o-transition: all 2s linear;
						transition: all 2s linear;	
				}
				.switchMeter:checked + .progress .meter{
					width:100%;
				}
			</style>
		</div>
	</div>
	<div class="two columns">
		<div class="panel"></div>
	</div>
</div>