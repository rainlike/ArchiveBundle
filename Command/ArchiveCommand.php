<?php

namespace Rainlike\ArchiveBundle\Command;

use \Exception as SimpleException;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\HttpFoundation\StreamedResponse;

class ArchiveCommand extends ContainerAwareCommand
{
    /**
     * Command configuration
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('rainlike:archive')
            ->setAliases(['rl:archive'])
            ->setDescription('archive some directory')
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'The path to the folder for archive'
            )
            ->addOption(
                'type',
                null,
                InputOption::VALUE_REQUIRED,
                'The type of archive: zip or tar',
                'tar'
            )
            ->addOption(
                'use_linux',
                null,
                InputOption::VALUE_REQUIRED,
                'Use Linux commands for archive',
                false
            )
        ;
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //$output->writeln('<fg=black;bg=cyan>foo</>');
        //black red green yellow blue magenta cyan white

        $container = $this->getContainer();
        $translator = $container->get('translator');

        //$path = $container->getParameter('rainlike_google_drive_backups.dir_to_archive');
        $path = $input->getArgument('path');

        $type = $input->getOption('type');
        $useLinux = $input->getOption('use_linux');

        $tmpName = 'archive.tar';

        switch ($type) {
            case 'tar':
                try {
                    $archivator = new \PharData($tmpName);

                    $archivator->buildFromDirectory($path);
                    $archivator->compress(\Phar::GZ);
                    unlink($tmpName);
                    rename($tmpName.'.gz', './polygon/'.$tmpName.'.gz');
                } catch (SimpleException $exception) {
                    $exceptionText = 'Exception: ';
                    $output->writeln('<fg=red>'.$translator->trans($exceptionText).$exception->getMessage().'</>');
                }
        }

//        switch ($archiveType) {
//            case "tar":
//                try {
//                    $a = new PharData('archive.tar');
//
//                    // ADD FILES TO archive.tar FILE
//                    $a->addFile('data.xls');
//                    $a->addFile('index.php');
//
//                    // COMPRESS archive.tar FILE. COMPRESSED FILE WILL BE archive.tar.gz
//                    $a->compress(Phar::GZ);
//
//                    // NOTE THAT BOTH FILES WILL EXISTS. SO IF YOU WANT YOU CAN UNLINK archive.tar
//                    unlink('archive.tar');
//                } catch (Exception $e) {
//                    echo "Exception : ".$e;
//                }
//
//                break;
//            case "zip":
//                $images_dir = '/path/to/images';
//
//                //this folder must be writeable by the server
//                $backup = '/path/to/backup';
//                $zip_file = $backup.'/backup.zip';
//
//                if ($handle = opendir($images_dir)) {
//                    $zip = new ZipArchive();
//
//                    if ($zip->open($zip_file, ZIPARCHIVE::CREATE)!==TRUE) {
//                        exit("cannot open <$zip_file>\n");
//                    }
//
//                    while (false !== ($file = readdir($handle))) {
//                        $zip->addFile($images_dir.'/'.$file);
//                        echo "$file\n";
//                    }
//
//                    closedir($handle);
//
//                    echo "numfiles: ".$zip->numFiles."\n";
//                    echo "status:".$zip->status."\n";
//
//                    $zip->close();
//
//                    echo 'Zip File:'.$zip_file."\n";
//                }
//
//                break;
//        }
    }

}
