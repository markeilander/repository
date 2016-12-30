<?php

namespace Eilander\Repository\Contracts;

/**
 * Interface UploadRepository.
 */
interface Upload extends Repository
{
    /**
     * Upload file.
     *
     * @param string $file file to upload
     * @param array  $data
     *
     * @return mixed
     */
    public function upload($file, array $data = []);
}
