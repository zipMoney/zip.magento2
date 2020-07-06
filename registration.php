<?php
//support install without composer like FTP or saved code in git
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Zip_ZipPayment',
    __DIR__
);
