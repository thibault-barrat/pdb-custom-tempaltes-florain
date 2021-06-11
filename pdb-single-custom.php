<?php
/**
 * @name pdb single flex template custom
 * @version 1.0
 * 
 * custom emplate for displaying a single record with a custom SQL query
 *
 */
if ( $this->participant_id > 0 ) :
?>

<div class="wrap <?php echo $this->wrap_class ?> pdb-single-flex">
	
  <?php while ( $this->have_groups() ) : $this->the_group(); ?>
  
  <section class="section <?php $this->group->print_class() ?>" id="<?php echo Participants_Db::$prefix.$this->group->name ?>">
  
    <?php $this->group->print_title( '<h2 class="pdb-group-title">', '</h2>' ) ?>
    
    <?php $this->group->print_description() ?>
    
    
      <?php while ( $this->have_fields() ) : $this->the_field();
					
          // CSS class for empty fields
					$empty_class = $this->get_empty_class( $this->field );
      
      ?>
    <div class="<?php echo Participants_Db::$prefix.$this->field->name.' '.$this->field->form_element.' '.$empty_class?> flex-field">
      
      <span class="<?php echo $this->field->name.' '.$empty_class?> flex-label"><?php $this->field->print_label() ?></span>
      
      <span class="<?php echo $this->field->name.' '.$empty_class?> flex-value"><?php $this->field->print_value() ?></span>
    
    </div>
  
    	<?php endwhile; // end of the fields loop ?>
    
  </section>
  
  <?php endwhile; // end of the groups loop ?>

  <?php 
  if (Participants_Db::get_participant($this->participant_id)['categorie'] == 'Marchés' ) {
      $titre = Participants_Db::get_participant($this->participant_id)['titre'];
      $filter = 'présence_sur_les_marchés=*' . $titre . '*';
      $acteurs = Participants_Db::get_participant_list( array('fields' => 'titre, categorie','filter' => $filter ) );
      if (!empty($acteurs)) {
          $acteursPresents = '';
          foreach ($acteurs as $acteur) {
              $id = $acteur->id;
              $titre = $acteur->titre;
              $link = add_query_arg('pdb', $id, strtok($_SERVER["REQUEST_URI"], '?'));
              $acteursPresents .= '<a href="' . $link . '">' . $titre . '</a>, '; 
          }
          $acteursPresents = trim($acteursPresents, ', ');
          echo $_SERVER['REQUEST_URI'];
          echo '<div class="pdb-acteurs_presents text-area  flex-field">
      
          <span class="acteurs_presents  flex-label">Acteurs présents</span>
          
          <span class="acteurs_presents  flex-value"><span class="textarea">' . $acteursPresents . '</span></span>
        
        </div>';
      }
  }
  ?>
  
</div>
<?php else : // content to show if no record is found ?>

  <?php $error_message = Participants_Db::plugin_setting( 'no_record_error_message', '' );
  
  if ( ! empty( $error_message ) ) : ?>

    <p class="alert alert-error"><?php echo $error_message ?></p>
    
  <?php endif ?>
    
<?php endif ?>