<?php
/**
 * BucketManagementInterface
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Storage;

use Alpha\Connector\RepositoryInterface;

/**
 * Defines the interface of the operations from Bucket to Repository.
 */
interface BucketManagementInterface
{
    /**
     * Called when the Bucket is being created.
     * 
     * @param \Alpha\Connector\RepositoryInterface $repo The repository.
     * 
     * @return void
     */
    function onCreate(RepositoryInterface $repo);
    
    /**
     * Called when the Bucket is being destroyed.
     * 
     * @param \Alpha\Connector\RepositoryInterface $repo The repository.
     * 
     * @return void
     */
    function onDestroy(RepositoryInterface $repo);
    
    /**
     * Called when the Bucket is being upgraded.
     * 
     * @param \Alpha\Connector\RepositoryInterface $repo       The repository.
     * @param int                                  $oldVersion The id of the old version.
     * @param int                                  $newVersion The id of the new version.
     * 
     * @return void
     */
    function onUpgrade(RepositoryInterface $repo, $oldVersion, $newVersion);
    
    /**
     * Called when the Bucket is being downgraded.
     * 
     * @param \Alpha\Connector\RepositoryInterface $repo       The repository.
     * @param int                                  $oldVersion The id of the old version.
     * @param int                                  $newVersion The id of the new version.
     * 
     * @return void
     */
    function onDowngrade(RepositoryInterface $repo, $oldVersion, $newVersion);
}