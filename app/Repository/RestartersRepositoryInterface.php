<?php

namespace App\Repository;

use App\Models\Organisation;

interface RestartersRepositoryInterface
{
    public function getGroups();

    public function getGroup($id);

    public function syncOrganisationData(Organisation $organisation);
}
