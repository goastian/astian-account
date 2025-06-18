<?php
namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface Contracts
{
    /**
     * Search resources
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function search(Request $request);

    /**
     * Create new resource
     * @param array $data
     * @return void
     */
    public function create(array $data);

    /**
     * Search specific resource
     * @param string $id
     * @return void
     */
    public function find(string $id);

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update(string $id, array $data);

    /**
     * Delete specific resource
     * @param string $id 
     * @return void
     */
    public function delete(string $id);
}
