<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class SmsLogController extends Controller
{
    /**
     * API 1 : écrit ip, user_agent, mobile, senderid et sms dans le log du jour.
     */
    public function store(Request $request)
    {
        $dir = config('sms.log_path');
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $entry = [
            'date'       => date('Y-m-d H:i:s'),
            'ip'         => $request->input('ip', $request->ip()),
            'user_agent' => $request->input('user_agent', $request->userAgent()),
            'mobile'     => $request->input('mobile', $request->input('mobiles')),
            'senderid'   => $request->input('senderid'),
            'sms'        => $request->input('sms'),
        ];

        $file = $dir.DIRECTORY_SEPARATOR.'sms-'.date('Y-m-d').'.log';
        $line = json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).PHP_EOL;
        file_put_contents($file, $line, FILE_APPEND | LOCK_EX);

        return response('OK', 200)->header('Content-Type', 'text/plain');
    }

    /**
     * API 2 : retourne le dernier fichier de log écrit (le plus récemment modifié).
     * Avec ?download=1, force le téléchargement.
     */
    public function latest(Request $request)
    {
        $key = config('sms.read_key');
        if ($key !== '' && $request->header('X-Api-Key') !== $key) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $files = glob(config('sms.log_path').DIRECTORY_SEPARATOR.'*.log') ?: [];
        if (!$files) {
            return response()->json(['error' => 'No log file found'], 404);
        }

        usort($files, function ($a, $b) {
            return filemtime($b) <=> filemtime($a);
        });
        $latest = $files[0];

        if ($request->boolean('download')) {
            return response()->download($latest, basename($latest), ['Content-Type' => 'text/plain']);
        }

        return response(file_get_contents($latest), 200)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('X-Log-File', basename($latest));
    }
}
