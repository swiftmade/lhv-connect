<?php

namespace Swiftmade\LhvConnect\Xml;

class Tag
{
    /**
     * Request types
     */
    const ACCOUNT_STATEMENT_REQUEST = 'AcctRptgReq';
    const MERCHANT_REPORT_REQUEST = 'MerchantReportRequest';
    const PAYMENT_INITIATION_REQUEST = 'CstmrCdtTrfInitn';

    /**
     * Response types
     */
    const ACCOUNT_STATEMENT_RESPONSE = 'BkToCstmrStmt';
    const MERCHANT_REPORT_RESPONSE = 'BkToCstmrDbtCdtNtfctn';

    /**
     * Elements
     */
    const ACCOUNT = 'Acct';
    const ACCOUNT_IDENTIFICATION = 'Id';
    const ACCOUNT_OWNER = 'AcctOwnr';
    const AMOUNT = 'Amt';
    const BATCH_BOOKING = 'BtchBookg';
    const BENEFICIARY = 'Cdtr';
    const BENEFICIARY_ACCOUNT = 'CdtrAcct';
    const BENEFICIARY_ACCOUNT_IDENTIFICATION = 'Id';
    const BENEFICIARY_AGENT = 'CdtrAgt';
    const BENEFICIARY_NAME = 'Nm';
    const BENEFICIARY_REFERENCE_INFORMATION = 'CdtrRefInf';
    const BIC = 'BIC';
    const CHARGES_BEARER = 'ChrgBr';
    const CONTROL_SUM = 'CtrlSum';
    const CREATION_DATETIME = 'CreDtTm';
    const CREDIT_TRANSFER_TRANSACTION_INFORMATION = 'CdtTrfTxInf';
    const CURRENCY = 'Ccy';
    const DOCUMENT = 'Document';
    const END_TO_END_IDENTIFICATION = 'EndToEndId';
    const FINANCIAL_INSTITUTION_IDENTIFICATION = 'FinInstnId';
    const FROM_DATE = 'FrDt';
    const FROM_TIME = 'FrTm';
    const FROM_TO_DATE = 'FrToDt';
    const FROM_TO_TIME = 'FrToTm';
    const GROUP_HEADER = 'GrpHdr';
    const IBAN = 'IBAN';
    const IDENTIFICATION = 'Id';
    const INITIATING_PARTY = 'InitgPty';
    const INNER_CONTROL_SUM = 'CtrlSum';
    const INNER_NUMBER_OF_TRANSACTIONS = 'NbOfTxs';
    const INSTRUCTED_AMOUNT = 'InstdAmt';
    const LOCAL_INSTRUMENT = 'LclInstrm';
    const MERCHANT_PAYMENT_TYPE = 'Type';
    const MESSAGE_IDENTIFICATION = 'MsgId';
    const NAME = 'Nm';
    const NUMBER_OF_TRANSACTIONS = 'NbOfTxs';
    const PARTY = 'Pty';
    const PAYMENT_IDENTIFICATION = 'PmtId';
    const PAYMENT_INFORMATION = 'PmtInf';
    const PAYMENT_INFORMATION_IDENTIFICATION = 'PmtInfId';
    const PAYMENT_METHOD = 'PmtMtd';
    const PAYMENT_TYPE_INFORMATION = 'PmtTpInf';
    const PERIOD_END = 'PeriodEnd';
    const PERIOD_START = 'PeriodStart';
    const PROPRIETARY = 'Prtry';
    const REFERENCE = 'Ref';
    const REMITTANCE_INFORMATION = 'RmtInf';
    const REMITTER = 'Dbtr';
    const REMITTER_ACCOUNT = 'DbtrAcct';
    const REMITTER_ACCOUNT_IDENTIFICATION = 'Id';
    const REMITTER_AGENT = 'DbtrAgt';
    const REMITTER_NAME = 'Nm';
    const REPORTING_PERIOD = 'RptgPrd';
    const REPORTING_REQUEST = 'RptgReq';
    const REQUESTED_EXECUTION_DATE = 'ReqdExctnDt';
    const REQUESTED_MESSAGE_NAME_IDENTIFICATION = 'ReqdMsgNmId';
    const STRUCTURED = 'Strd';
    const TO_DATE = 'ToDt';
    const TO_TIME = 'ToTm';
    const TYPE = 'Tp';
    const UNSTRUCTURED = 'Ustrd';
}
