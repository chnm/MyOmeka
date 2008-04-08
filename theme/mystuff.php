<?php head(); ?>	

	<div id="primary">
		<div id="my-tags">
		    <h3>MyTags</h3> 
		</div>
		
		<div id="my-annotations">
		    <h3>MyAnnotations</h3>
		    <ul>
		        <?php for($i=0;$i<5;$i++): ?>
		        <li>
		            <div class="title">
		                Item #<?php echo $i; ?>
		            </div>
		            <div class="snippet">
		                Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Proin lectus lacus, pharetra id, egestas eu, ultricies et, urna. In hac habitasse cras amet...
		            </div>
		        </li>
		        <?php endfor; ?>
		    </ul>
		</div>

		<div id="my-posters">
		    <h3>MyPosters</h3>
		        
		    <ul>
		        <?php for($i=9;$i<=13;$i++): ?>
		        <li>
    		        <div class="thumbnail">
    		            <img src="http://chnm.gmu.edu/staff/kris/stable-0.9/files/display/<?php echo $i; ?>/square_thumbnail" />
    		        </div>
    		        <div class="title">
    		            Item #<?php echo $i; ?>
    		        </div>
    		        <?php endfor; ?>
		        </li>
		    </ul>
		</div>
	</div>
	
<?php foot(); ?>