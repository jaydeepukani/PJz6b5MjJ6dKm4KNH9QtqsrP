<?php

namespace App\Http\Controllers\Api\Youtube;

use App\Helper\YoutubeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Youtube\Channel\GetChannelDetailsFromID;
use App\Http\Requests\Api\Youtube\Channel\GetChannelListFromName;
use App\Http\Requests\Api\Youtube\Channel\GetTopSubscribedChannelsList;

class ChannelController extends Controller
{
    public function getChannelListFromName(GetChannelListFromName $request)
    {
        $yt = new YoutubeHelper();
        $service = $yt->getYoutubeService();
        $result = $service->search->listSearch(
            'id',
            [
                'q' => $request->channelName,
                'type' => 'channel',
                'maxResults' => 2,
            ]
        );

        $ids = [];
        foreach ($result as $value) {
            if (empty($value->id->channelId)) {
                continue;
            }
            $ids[] = $value->id->channelId;
        }
        $ids = implode(',', $ids);

        $channelList = $service->channels->listChannels('id,snippet,statistics', ['id' => $ids]);

        return response()->json($channelList, 200);
    }

    public function getChannelDetailsFromID(GetChannelDetailsFromID $request)
    {
        $yt = new YoutubeHelper();
        $service = $yt->getYoutubeService();
        $result = $service->channels->listChannels('id,snippet,statistics,topicDetails,contentDetails', ['id' => $request->id]);
        return response()->json($result, 200);
    }

    public function getTopChannelsList(GetTopSubscribedChannelsList $request)
    {
        $yt = new YoutubeHelper();
        $service = $yt->getYoutubeService();
        $result = $service->search->listSearch(
            'id',
            [
                'type' => 'channel',
                'maxResults' => $request->maxResults ?? 50,
                'order' => $request->order ?? 'rating',
            ]
        );

        $ids = [];
        foreach ($result as $value) {
            if (empty($value->id->channelId)) {
                continue;
            }
            $ids[] = $value->id->channelId;
        }
        $ids = implode(',', $ids);

        $channelList = $service->channels->listChannels('id,snippet,statistics', ['id' => $ids]);

        return response()->json($channelList, 200);
    }
}
