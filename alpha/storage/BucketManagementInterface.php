<?php
/**
 * BucketManagementInterface
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Storage;

use Alpha\Connector\RepositoryConnectorInterface;

/**
 * Defines the interface of the operations from Bucket to Repository.
 */
interface BucketManagementInterface
{
    /**
     * Called when the Bucket is being created.
     * 
     * @param \Alpha\Connector\RepositoryConnectorInterface $repo The repository.
     * 
     * @return void
     * 
     * @throws \Exception
     */
    function onCreate(RepositoryConnectorInterface $repo);
    
    /**
     * Called when the Bucket is being destroyed.
     * 
     * @param \Alpha\Connector\RepositoryConnectorInterface $repo The repository.
     * 
     * @return void
     * 
     * @throws \Exception
     */
    function onDestroy(RepositoryConnectorInterface $repo);
    
    /**
     * Called when the Bucket is being upgraded.
     * 
     * @param \Alpha\Connector\RepositoryConnectorInterface $repo       The repository.
     * @param int                                           $oldVersion The id of the old version.
     * @param int                                           $newVersion The id of the new version.
     * 
     * @return void
     * 
     * @throws \Exception
     */
    function onUpgrade(RepositoryConnectorInterface $repo, $oldVersion, $newVersion);
    
    /**
     * Called when the Bucket is being downgraded.
     * 
     * @param \Alpha\Connector\RepositoryConnectorInterface $repo       The repository.
     * @param int                                           $oldVersion The id of the old version.
     * @param int                                           $newVersion The id of the new version.
     * 
     * @return void
     * 
     * @throws \Exception
     */
    function onDowngrade(RepositoryConnectorInterface $repo, $oldVersion, $newVersion);
}