<?php
namespace Mcustiel\Phiremock\Server\Actions;

use Mcustiel\PowerRoute\Actions\ActionInterface;
use Mcustiel\PowerRoute\Common\TransactionData;
use Mcustiel\Phiremock\Server\Model\ScenarioStorage;

class ClearScenariosAction implements ActionInterface
{
    private $storage;

    public function __construct(ScenarioStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Mcustiel\PowerRoute\Actions\ActionInterface::execute()
     */
    public function execute(TransactionData $transactionData)
    {
        $this->storage->clearScenarios();

        $transactionData->setResponse(
            $transactionData->getResponse()->withStatus(200)
        );
    }

    /**
     * {@inheritDoc}
     * @see \Mcustiel\PowerRoute\Common\ArgumentAwareInterface::setArgument()
     */
    public function setArgument($argument)
    {
    }
}
