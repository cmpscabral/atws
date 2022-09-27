<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion\Invoice;

use Rebelo\ATWs\ATWsException;
use Rebelo\ATWs\EFaturaMDVersion\DocumentTotals;
use Rebelo\Date\Date;

/**
 * Commercial Document Data (InvoiceData)
 * Commercial Documents to customers.
 * @since 2.0.0
 */
class InvoiceData
{

    /**
     * @var \Logger
     * @since 2.0.0
     */
    protected \Logger $log;

    /**
     * @param \Rebelo\ATWs\EFaturaMDVersion\Invoice\InvoiceHeader $invoiceHeader          The invoice header
     * @param \Rebelo\ATWs\EFaturaMDVersion\Invoice\InvoiceStatus $documentStatus         The actual invoice status
     * @param string                                              $hashCharacters         It must contain the 1st, 11th, 21st and 31st characters of the document's Hash or the value “0” (zero), if the document is generated by a non-certified program.
     * @param bool                                                $cashVATSchemeIndicator Indicator of the existence of adhering to the Cash VAT regime. It must be filled in with “1” if there is adhesion and with “0” (zero) otherwise.
     * @param bool                                                $paperLessIndicator     Paperless invoice issuance indicator. It must be filled in with “1” if the invoice is issued without paper and with “0” (zero) otherwise.
     * @param string|null                                         $eacCode                The CAE code of the activity related to the issuance of this document must be indicated.
     * @param \Rebelo\Date\Date                                   $systemEntryDate        Record recording date to the second, at the time of signature.
     * @param \Rebelo\ATWs\EFaturaMDVersion\Line[]                $lines                  Document Lines by Rate (Line)
     * @param \Rebelo\ATWs\EFaturaMDVersion\DocumentTotals        $documentTotals         The Document Totals
     * @param \Rebelo\ATWs\EFaturaMDVersion\WithholdingTax[]|null $withholdingTax         Withholding Tax
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\ATWs\ATWsException
     * @since 2.0.0
     */
    public function __construct(
        protected InvoiceHeader  $invoiceHeader,
        protected InvoiceStatus  $documentStatus,
        protected string         $hashCharacters,
        protected bool           $cashVATSchemeIndicator,
        protected bool           $paperLessIndicator,
        protected ?string        $eacCode,
        protected Date           $systemEntryDate,
        protected array          $lines,
        protected DocumentTotals $documentTotals,
        protected ?array         $withholdingTax
    )
    {
        $this->log = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);

        $this->log->info("Hash characters set to " . $this->hashCharacters);
        $this->log->info(
            \sprintf(
                "Cash VAT Scheme Indicator set to %s",
                $this->cashVATSchemeIndicator ? "true" : "false"
            )
        );

        $this->log->info(
            \sprintf(
                "Paper less Indicator set to %s",
                $this->paperLessIndicator ? "true" : "false"
            )
        );

        $this->log->info("EACCode set to " . ($this->eacCode ?? "null"));

        if ($this->eacCode !== null && \preg_match("/^[0-9]{5}$/", $this->eacCode) !== 1) {
            $msg = "Invoice EACCode must respect the regexp ^[0-9]{5}$";
            $this->log->error($msg);
            throw new ATWsException($msg);
        }

        $this->log->info("SystemEntryDate set to " . $this->systemEntryDate->format(Date::DATE_T_TIME));
    }

    /**
     * It must contain the 1st, 11th, 21st and 31st characters of the document's Hash or the value “0” (zero),
     * if the document is generated by a non-certified program.
     * @return string
     * @since 2.0.0
     */
    public function getHashCharacters(): string
    {
        return $this->hashCharacters;
    }

    /**
     * Indicator of the existence of adhering to the Cash VAT regime.
     * It must be filled in with “1” if there is adhesion and with “0” (zero) otherwise.
     * @return bool
     * @since 2.0.0
     */
    public function getCashVATSchemeIndicator(): bool
    {
        return $this->cashVATSchemeIndicator;
    }

    /**
     * Paperless invoice issuance indicator. It must be filled in with “1”
     * if the invoice is issued without paper and with “0” (zero) otherwise.
     * @return bool
     * @since 2.0.0
     */
    public function getPaperLessIndicator(): bool
    {
        return $this->paperLessIndicator;
    }

    /**
     * The CAE code of the activity related to the issuance of this document must be indicated.
     * @return string|null
     * @since 2.0.0
     */
    public function getEacCode(): ?string
    {
        return $this->eacCode;
    }

    /**
     * Record recording date to the second, at the time of signature.
     * @return \Rebelo\Date\Date
     * @since 2.0.0
     */
    public function getSystemEntryDate(): Date
    {
        return $this->systemEntryDate;
    }

    /**
     * The document lines
     * @return array
     * @since 2.0.0
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    /**
     * The document totals
     * @return \Rebelo\ATWs\EFaturaMDVersion\DocumentTotals
     * @since 2.0.0
     */
    public function getDocumentTotals(): DocumentTotals
    {
        return $this->documentTotals;
    }

    /**
     * Withholding Tax
     * @return array|null
     * @since 2.0.0
     */
    public function getWithholdingTax(): ?array
    {
        return $this->withholdingTax;
    }

    /**
     * Get the actual invoice status
     * @return \Rebelo\ATWs\EFaturaMDVersion\Invoice\InvoiceStatus
     * @since 2.0.0
     */
    public function getDocumentStatus(): InvoiceStatus
    {
        return $this->documentStatus;
    }

    /**
     * The invoice header
     * @return \Rebelo\ATWs\EFaturaMDVersion\Invoice\InvoiceHeader
     * @since 2.0.0
     */
    public function getInvoiceHeader(): InvoiceHeader
    {
        return $this->invoiceHeader;
    }

}
