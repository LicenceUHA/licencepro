<?php
namespace Drupal\hello\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Database\Connection;

/**
 * Provides a hello block.
 * 
 * @Block(
 *  id = "hellosession_block",
 *  admin_label = @Translation("Hellosession!")
 * )
 */
class Hellosession extends BlockBase {

    /**
     * Implements Drupal\Core\Block\BlockBase::build().
     */
    public function build(){
       
            $session = \Drupal::database()->select('sessions')
            ->countQuery()
            ->execute()
            ->fetchField();

            return[
                '#markup' => $this->t('session number: %number', ['%number' =>$session]),
                '#cache' => [
                    'keys' => ['hello:sessions'],
                    'max-age' => '60',
                            ],
                 ];
                        
            }

    protected function blockAccess(AccountInterface $account){
        return AccessResult::allowedIfHasPermission($account, 'access hello');
    }
}