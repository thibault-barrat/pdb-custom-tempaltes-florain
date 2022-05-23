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

<?php
//Links to previous and next participants
$liste = Participants_Db::get_id_list( array( 'orderby' => 'id', 'filter' => 'inactif!=true' ) );
$idPrevious = $liste[array_search($this->participant_id, $liste) + 1];
$idNext = $liste[array_search($this->participant_id, $liste) - 1]; 
$linkPrevious = esc_url(add_query_arg('pdb', $idPrevious, strtok($_SERVER["REQUEST_URI"], '?')));
$linkNext = esc_url(add_query_arg('pdb', $idNext, strtok($_SERVER["REQUEST_URI"], '?')));

?>
<div class="acteur-nav acteur-nav-prev"><a href="<?php echo $linkPrevious ?>"><i class="fas fa-chevron-circle-left"></i><p>Précédent</p></a></div>
<div class="acteur-nav acteur-nav-next"><a href="<?php echo $linkNext ?>"><i class="fas fa-chevron-circle-right"></i><p>Suivant</p></a></div>
	
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
      
      <span class="<?php echo $this->field->name.' '.$empty_class?> flex-value">
        <?php 
        //if présence_sur_les_marchés not empty, we add a link to the marchés
        if ($this->field->name == "présence_sur_les_marchés" && $empty_class!= 'blank-field') {
            $marches = $this->field->get_value('présence_sur_les_marchés')['other'];
            $marches = explode(', ', $marches);
            foreach($marches as $key => $marche) {
                $filter = 'titre=' . $marche;
                $id = Participants_Db::get_id_list(array('filter' => $filter))[0];
                $link = esc_url(add_query_arg('pdb', $id, strtok($_SERVER["REQUEST_URI"], '?')));
                $marches[$key] = '<a href="' . $link . '">' . $marche . '</a>';
            }
            $marches = implode(', ', $marches);
            echo $marches;
        }
        else {
            $this->field->print_value();
        } 
        ?>
      </span>
    
    </div>
  
    	<?php endwhile; // end of the fields loop ?>
    
  </section>
  
  <?php endwhile; // end of the groups loop ?>

  <?php 
  //specific field for the categorie "Marchés"
  if (Participants_Db::get_participant($this->participant_id)['categorie'] == 'Marchés' ) {
      $titre = Participants_Db::get_participant($this->participant_id)['titre'];
      $filter = 'présence_sur_les_marchés~' . $titre;
      $acteurs = Participants_Db::get_participant_list( array('fields' => 'titre, categorie, inactif','filter' => $filter ) );
      if (!empty($acteurs)) {
          $acteursPresents = '';
          foreach ($acteurs as $acteur) {
              $id = $acteur->id;
              $titre = $acteur->titre;
              $inactif = $acteur->inactif;
              if($inactif != 'true') {
                $link = esc_url(add_query_arg('pdb', $id, strtok($_SERVER["REQUEST_URI"], '?')));
                $acteursPresents .= '<a href="' . $link . '">' . $titre . '</a>, '; 
              }             
          }
          $acteursPresents = trim($acteursPresents, ', ');
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