<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Flex
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Twilio\Rest\FlexApi\V1;

use Twilio\Exceptions\TwilioException;
use Twilio\ListResource;
use Twilio\Options;
use Twilio\Stream;
use Twilio\Values;
use Twilio\Version;
use Twilio\Serialize;


class InsightsQuestionnairesQuestionList extends ListResource
    {
    /**
     * Construct the InsightsQuestionnairesQuestionList
     *
     * @param Version $version Version that contains the resource
     */
    public function __construct(
        Version $version
    ) {
        parent::__construct($version);

        // Path Solution
        $this->solution = [
        ];

        $this->uri = '/Insights/QM/Questions';
    }

    /**
     * Create the InsightsQuestionnairesQuestionInstance
     *
     * @param string $categoryId The ID of the category
     * @param string $question The question.
     * @param string $description The description for the question.
     * @param string $answerSetId The answer_set for the question.
     * @param bool $allowNa The flag to enable for disable NA for answer.
     * @param array|Options $options Optional Arguments
     * @return InsightsQuestionnairesQuestionInstance Created InsightsQuestionnairesQuestionInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function create(string $categoryId, string $question, string $description, string $answerSetId, bool $allowNa, array $options = []): InsightsQuestionnairesQuestionInstance
    {

        $options = new Values($options);

        $data = Values::of([
            'CategoryId' =>
                $categoryId,
            'Question' =>
                $question,
            'Description' =>
                $description,
            'AnswerSetId' =>
                $answerSetId,
            'AllowNa' =>
                Serialize::booleanToString($allowNa),
        ]);

        $headers = Values::of(['Token' => $options['token']]);

        $payload = $this->version->create('POST', $this->uri, [], $data, $headers);

        return new InsightsQuestionnairesQuestionInstance(
            $this->version,
            $payload
        );
    }


    /**
     * Reads InsightsQuestionnairesQuestionInstance records from the API as a list.
     * Unlike stream(), this operation is eager and will load `limit` records into
     * memory before returning.
     *
     * @param array|Options $options Optional Arguments
     * @param int $limit Upper limit for the number of records to return. read()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, read()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return InsightsQuestionnairesQuestionInstance[] Array of results
     */
    public function read(array $options = [], int $limit = null, $pageSize = null): array
    {
        return \iterator_to_array($this->stream($options, $limit, $pageSize), false);
    }

    /**
     * Streams InsightsQuestionnairesQuestionInstance records from the API as a generator stream.
     * This operation lazily loads records as efficiently as possible until the
     * limit
     * is reached.
     * The results are returned as a generator, so this operation is memory
     * efficient.
     *
     * @param array|Options $options Optional Arguments
     * @param int $limit Upper limit for the number of records to return. stream()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, stream()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return Stream stream of results
     */
    public function stream(array $options = [], int $limit = null, $pageSize = null): Stream
    {
        $limits = $this->version->readLimits($limit, $pageSize);

        $page = $this->page($options, $limits['pageSize']);

        return $this->version->stream($page, $limits['limit'], $limits['pageLimit']);
    }

    /**
     * Retrieve a single page of InsightsQuestionnairesQuestionInstance records from the API.
     * Request is executed immediately
     *
     * @param mixed $pageSize Number of records to return, defaults to 50
     * @param string $pageToken PageToken provided by the API
     * @param mixed $pageNumber Page Number, this value is simply for client state
     * @return InsightsQuestionnairesQuestionPage Page of InsightsQuestionnairesQuestionInstance
     */
    public function page(
        array $options = [],
        $pageSize = Values::NONE,
        string $pageToken = Values::NONE,
        $pageNumber = Values::NONE
    ): InsightsQuestionnairesQuestionPage
    {
        $options = new Values($options);

        $params = Values::of([
            'CategoryId' =>
                Serialize::map($options['categoryId'], function ($e) { return $e; }),
            'Token' =>
                $options['token'],
            'PageToken' => $pageToken,
            'Page' => $pageNumber,
            'PageSize' => $pageSize,
        ]);

        $response = $this->version->page('GET', $this->uri, $params);

        return new InsightsQuestionnairesQuestionPage($this->version, $response, $this->solution);
    }

    /**
     * Retrieve a specific page of InsightsQuestionnairesQuestionInstance records from the API.
     * Request is executed immediately
     *
     * @param string $targetUrl API-generated URL for the requested results page
     * @return InsightsQuestionnairesQuestionPage Page of InsightsQuestionnairesQuestionInstance
     */
    public function getPage(string $targetUrl): InsightsQuestionnairesQuestionPage
    {
        $response = $this->version->getDomain()->getClient()->request(
            'GET',
            $targetUrl
        );

        return new InsightsQuestionnairesQuestionPage($this->version, $response, $this->solution);
    }


    /**
     * Constructs a InsightsQuestionnairesQuestionContext
     *
     * @param string $questionId The unique ID of the question
     */
    public function getContext(
        string $questionId
        
    ): InsightsQuestionnairesQuestionContext
    {
        return new InsightsQuestionnairesQuestionContext(
            $this->version,
            $questionId
        );
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        return '[Twilio.FlexApi.V1.InsightsQuestionnairesQuestionList]';
    }
}